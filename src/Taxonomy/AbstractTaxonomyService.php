<?php

namespace ActiveLAMP\Taxonomy\Taxonomy;

use ActiveLAMP\Taxonomy\Entity\EntityTermInterface;
use ActiveLAMP\Taxonomy\Entity\TermInterface;
use ActiveLAMP\Taxonomy\Entity\VocabularyInterface;
use ActiveLAMP\Taxonomy\Entity\VocabularyFieldInterface;
use ActiveLAMP\Taxonomy\Metadata\MetadataFactory;
use ActiveLAMP\Taxonomy\Metadata\Reader\AnnotationReader;
use ActiveLAMP\Taxonomy\Metadata\TaxonomyMetadata;
use ActiveLAMP\Taxonomy\Model\EntityTermRepositoryInterface;
use ActiveLAMP\Taxonomy\Model\TermRepositoryInterface;
use ActiveLAMP\Taxonomy\Model\VocabularyRepositoryInterface;
use ActiveLAMP\Taxonomy\Model\VocabularyFieldFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
abstract class AbstractTaxonomyService implements TaxonomyServiceInterface
{
    /**
     * @var VocabularyRepositoryInterface
     */
    protected $vocabularies;

    /**
     * @var TermRepositoryInterface
     */
    protected $terms;

    /**
     * @var EntityTermRepositoryInterface
     */
    protected $entityTerms;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $em;

    /**
     * @var TaxonomyMetadata|null
     */
    protected $metadata;

    /**
     * @var TaxonomizedEntityManager
     */
    protected $taxonomizedEntityManager;

    /**
     * @var \ActiveLAMP\Taxonomy\Metadata\MetadataFactory
     */
    protected $metadataFactory;

    /**
     * @var string
     */
    protected $vocabularyClass;

    /**
     * @var string
     */
    protected $termClass;

    /**
     * @var string
     */
    protected $entityTermClass;

    /**
     * @param ObjectManager $em
     * @param $vocabularyClass
     * @param $termClass
     * @param $entityTermClass
     */
    public function __construct(
        ObjectManager $em,
        $vocabularyClass,
        $termClass,
        $entityTermClass
    ) {
        $this->em = $em;

        $this->vocabularies =
            $em->getRepository($vocabularyClass);
        $this->terms =
            $em->getRepository($termClass);
        $this->entityTerms =
            $em->getRepository($entityTermClass);

        $this->vocabularyClass = $vocabularyClass;
        $this->termClass = $termClass;
        $this->entityTermClass = $entityTermClass;

        $this->metadataFactory =
            new MetadataFactory(new AnnotationReader());
        $this->taxonomizedEntityManager =
            new TaxonomizedEntityManager($this, new VocabularyFieldFactory($em, $this->entityTerms));

    }

    /**
     * @return TaxonomyMetadata
     */
    public function getMetadata()
    {
        if ($this->metadata === null) {
            $this->metadata = $this->metadataFactory->getMetadata($this->em);
        }
        return $this->metadata;
    }

    /**
     * @return \ActiveLAMP\Taxonomy\Entity\VocabularyInterface[]|array
     */
    public function findAllVocabularies()
    {
        return $this->vocabularies->findAll();
    }

    /**
     * @return \ActiveLAMP\Taxonomy\Entity\TermInterface[]|array
     */
    public function findAllTerms()
    {
        return $this->terms->findAll();
    }

    /**
     * @param $name
     * @return \ActiveLAMP\Taxonomy\Entity\VocabularyInterface[]
     */
    public function findVocabularyByName($name)
    {
        return $this->vocabularies->findByName($name);
    }

    /**
     * @param $vocabulary
     * @return ArrayCollection
     */
    public function findTermsInVocabulary($vocabulary)
    {
        if (is_scalar($vocabulary)) {
            $vocabulary = $this->findVocabularyByName($vocabulary);
        }

        return new ArrayCollection($this->terms->findByVocabulary($vocabulary));
    }

    /**
     * @param $id
     * @return TermInterface
     */
    public function findTermById($id)
    {
        return $this->terms->findById($id);
    }

    /**
     * @param $name
     * @return TermInterface
     */
    public function findTermByName($name)
    {
        return $this->terms->findByName($name);
    }

    /**
     * @param TermInterface $term
     */
    public function deleteTerm(TermInterface $term)
    {
        $this->em->remove($term);
        $this->em->flush();
    }

    /**
     * @param TermInterface $term
     * @param bool $flush
     * @throws \DomainException
     */
    public function saveTerm(TermInterface $term, $flush = true)
    {
        if (!$term->getVocabulary()) {
            throw new \DomainException('Term must be assigned to a vocabulary before persisting it.');
        }

        $this->em->persist($term);

        if ($flush === true) {
            $this->em->flush();
        }
    }

    /**
     * @param VocabularyInterface $vocabulary
     */
    public function deleteVocabulary(VocabularyInterface $vocabulary)
    {
        $this->em->remove($vocabulary);
        $this->em->flush();
    }

    /**
     * @param VocabularyInterface $vocabulary
     * @param $flush
     */
    public function saveVocabulary(VocabularyInterface $vocabulary, $flush = true)
    {
        $this->em->persist($vocabulary);

        if ($flush === true) {
            $this->em->flush();
        }
    }

    /**
     * @param EntityTermInterface $entityTerm
     * @param bool $flush
     * @throws \LogicException
     */
    public function saveEntityTerm(EntityTermInterface $entityTerm, $flush = true)
    {
        $entity = $entityTerm->getEntity();
        $metadata = $this->getMetadata()->getEntityMetadata($entity);
        $id = $metadata->extractIdentifier($entity);

        if ($id == null) {
            throw new \LogicException('The entity you wish to tag must be persisted first. Identifier cannot be null or false.');
        }

        $entityTerm->setEntityIdentifier($id);
        $entityTerm->setEntityType($metadata->getType());

        $this->em->persist($entityTerm);

        if ($flush === true) {
            $this->em->flush();
        }
    }

    /**
     * @param $entity
     * @throws \RuntimeException
     */
    public function loadVocabularyFields($entity)
    {
        $this->taxonomizedEntityManager->mountVocabularyFields($entity);
    }

    /**
     * @param $entity
     * @param bool $flush
     */
    public function saveTaxonomies($entity, $flush = true)
    {
        $metadata = $this->getMetadata()->getEntityMetadata($entity);
        $dirty = false;

        foreach ($metadata->getVocabularies() as $vocabMetadata) {

            $field = $vocabMetadata->extractValueInField($entity);

            if (!$field instanceof VocabularyFieldInterface) {
                $this->taxonomizedEntityManager->mountVocabularyField($entity, $vocabMetadata->getName());
                $field = $vocabMetadata->extractValueInField($entity);
            }

            if (!$field->isDirty()) {
                continue;
            }

            $inserts = $field->getInsertDiff();
            $deletes = $field->getDeleteDiff();

            /** @var $eTerm EntityTermInterface */
            foreach ($inserts as $eTerm) {
                $eTerm->setEntity($entity);
                $this->saveEntityTerm($eTerm, false);
                $dirty = true;
            }

            foreach ($deletes as $eTerm) {
                $this->em->remove($eTerm);
                $dirty = true;
            }

            $field->setDirty(false);
        }

        if ($dirty === true && $flush === true) {
            $this->em->flush();
        }
    }

    /**
     * @return ObjectManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @return string
     */
    public function getTermClass()
    {
        return $this->termClass;
    }

    /**
     * @return string
     */
    public function getVocabularyClass()
    {
        return $this->vocabularyClass;
    }

    /**
     * @return string
     */
    public function getEntityTermClass()
    {
        return $this->entityTermClass;
    }

    /**
     * @return VocabularyInterface
     */
    public function createVocabulary()
    {
        $class = $this->getVocabularyClass();
        return new $class();
    }

    /**
     * @param VocabularyInterface $vocabulary
     *
     * @throws \InvalidArgumentException
     * @return TermInterface
     */
    public function createTerm(VocabularyInterface $vocabulary = null)
    {
        $class = $this->getTermClass();

        /** @var $term TermInterface */
        $term = new $class();

        if ($vocabulary !== null) {

            if (!is_a($vocabulary, $this->getVocabularyClass())) {
                throw new \InvalidArgumentException('Vocabulary provided must be instance of ' . $this->getVocabularyClass());
            }

            $term->setVocabulary($vocabulary);
        }

        return $term;
    }

    /**
     * @return EntityTermInterface
     */
    public function createEntityTerm()
    {
        $class = $this->getEntityTermClass();
        return new $class();
    }

}
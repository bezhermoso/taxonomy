<?php
namespace ActiveLAMP\Taxonomy\Taxonomy;


use ActiveLAMP\Taxonomy\Entity\EntityTermInterface;
use ActiveLAMP\Taxonomy\Entity\TermInterface;
use ActiveLAMP\Taxonomy\Entity\VocabularyInterface;
use ActiveLAMP\Taxonomy\Metadata\TaxonomyMetadata;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
interface TaxonomyServiceInterface
{
    /**
     * @param $name
     * @return TermInterface
     */
    public function findTermByName($name);

    /**
     * @return TaxonomyMetadata
     */
    public function getMetadata();

    /**
     * @param VocabularyInterface $vocabulary
     * @param $flush
     * @return
     */
    public function saveVocabulary(VocabularyInterface $vocabulary, $flush = true);

    /**
     * @return VocabularyInterface[]|array
     */
    public function findAllVocabularies();

    /**
     * @param $entity
     * @param bool $flush
     */
    public function saveTaxonomies($entity, $flush = true);

    /**
     * @param EntityTermInterface $entityTerm
     * @param bool $flush
     * @throws \LogicException
     */
    public function saveEntityTerm(EntityTermInterface $entityTerm, $flush = true);

    /**
     * @param $entity
     * @throws \RuntimeException
     */
    public function loadVocabularyFields($entity);

    /**
     * @param TermInterface $term
     */
    public function deleteTerm(TermInterface $term);

    /**
     * @param $name
     * @return VocabularyInterface[]
     */
    public function findVocabularyByName($name);

    /**
     * @return ObjectManager
     */
    public function getEntityManager();

    /**
     * @param VocabularyInterface $vocabulary
     */
    public function deleteVocabulary(VocabularyInterface $vocabulary);

    /**
     * @param $id
     * @return TermInterface
     */
    public function findTermById($id);

    /**
     * @param TermInterface $term
     * @throws \DomainException
     * @param bool $flush
     */
    public function saveTerm(TermInterface $term, $flush = true);

    /**
     * @return TermInterface[]|array
     */
    public function findAllTerms();

    /**
     * @param $vocabulary
     * @return ArrayCollection
     */
    public function findTermsInVocabulary($vocabulary);

    /**
     * @return string
     */
    public function getTermClass();

    /**
     * @return string
     */
    public function getVocabularyClass();

    /**
     * @return string
     */
    public function getEntityTermClass();

    /**
     * @return VocabularyInterface
     */
    public function createVocabulary();

    /**
     * @param VocabularyInterface $vocabulary
     *
     * @return TermInterface
     */
    public function createTerm(VocabularyInterface $vocabulary = null);

    /**
     * @return EntityTermInterface
     */
    public function createEntityTerm();
}
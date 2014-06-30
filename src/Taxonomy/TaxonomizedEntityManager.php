<?php

namespace ActiveLAMP\Taxonomy\Taxonomy;

use ActiveLAMP\Taxonomy\Entity\Vocabulary;
use ActiveLAMP\Taxonomy\Entity\VocabularyFieldInterface;
use ActiveLAMP\Taxonomy\Model\VocabularyFieldFactory;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
class TaxonomizedEntityManager
{
    protected $metadata;

    protected $fieldFactory;

    protected $service;

    /**
     * @param TaxonomyServiceInterface $service
     * @param VocabularyFieldFactory $factory
     */
    public function __construct(
        TaxonomyServiceInterface $service,
        VocabularyFieldFactory $factory
    ) {
        $this->service = $service;
        $this->fieldFactory = $factory;
    }

    /**
     * @param $entity
     * @param $vocabulary
     * @throws \RuntimeException
     */
    public function mountVocabularyField($entity, $vocabulary)
    {
        if ($vocabulary instanceof Vocabulary) {
            $name = $vocabulary->getName();
        } else {
            $name = $vocabulary;
        }


        $taxonomyMetadata = $this->service->getMetadata();
        $metadata = $taxonomyMetadata->getEntityMetadata($entity);
        $vocabularyMetadata = $metadata->getVocabularyByName($name);

        $previousValue = $vocabularyMetadata->extractValueInField($entity);

        if ($previousValue instanceof VocabularyFieldInterface) {
            /**
             * Already injected.
             */
            return;
        }

        if (!$vocabulary instanceof Vocabulary) {
            $vocabulary = $this->service->findVocabularyByName($name);
            if (!$vocabulary) {
                throw new \RuntimeException(
                    sprintf(
                        'Cannot find "%s" vocabulary. Cannot mount taxonomies to %s::%s',
                        $vocabularyMetadata->getName(),
                        $metadata->getReflectionClass()->getName(),
                        $vocabularyMetadata->getReflectionProperty()->getName()
                    ));
            }
        }

        $field =
            $this->fieldFactory->createVocabularyField(
                $vocabulary,
                $metadata->getType(),
                $metadata->extractIdentifier($entity),
                $previousValue,
                $vocabularyMetadata->isSingular());

        $vocabularyMetadata->setVocabularyField($field, $entity);

    }

    /**
     * @param $entity
     * @throws \RuntimeException
     */
    public function mountVocabularyFields($entity)
    {
        $metadata = $this->service->getMetadata()->getEntityMetadata($entity);

        foreach ($metadata->getVocabularies() as $vocabularyMetadata) {
            $this->mountVocabularyField($entity, $vocabularyMetadata->getName());
        }
    }
}
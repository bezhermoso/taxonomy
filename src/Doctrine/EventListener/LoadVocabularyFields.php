<?php

namespace ActiveLAMP\Taxonomy\Doctrine\EventListener;

use ActiveLAMP\Taxonomy\Metadata\TaxonomyMetadata;
use ActiveLAMP\Taxonomy\Taxonomy\TaxonomyServiceInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
class LoadVocabularyFields implements EventSubscriber
{
    /**
     * @var TaxonomyMetadata
     */
    protected $metadata;

    /**
     * @var TaxonomyServiceInterface
     */
    protected $service;

    /**
     * @param TaxonomyServiceInterface $service
     */
    public function __construct(TaxonomyServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::postLoad
        );
    }

    /**
     * @return TaxonomyMetadata
     */
    protected function getMetadata()
    {
        if (null === $this->metadata) {
            $this->metadata = $this->service->getMetadata();
        }

        return $this->metadata;
    }

    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        $metadata = $this->getMetadata();

        if (!$metadata->hasEntityMetadata($entity)) {
            return;
        }

        $this->service->loadVocabularyFields($entity);

    }
}
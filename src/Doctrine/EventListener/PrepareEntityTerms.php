<?php

namespace ActiveLAMP\Taxonomy\Doctrine\EventListener;

use ActiveLAMP\Taxonomy\Entity\EntityTermInterface;
use ActiveLAMP\Taxonomy\Taxonomy\TaxonomyServiceInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
class PrepareEntityTerms implements EventSubscriber
{

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
            Events::prePersist,
        );
    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if (!$entity instanceof EntityTermInterface) {
            return;
        }

        $relEntity = $entity->getEntity();

        $metadata = $this->service->getMetadata()->getEntityMetadata($relEntity);
        $id = $metadata->extractIdentifier($relEntity);
        $entity->setEntityIdentifier($id);
        $entity->setEntityType($metadata->getType());

    }
}
<?php

namespace ActiveLAMP\Taxonomy\Doctrine\EventListener;

use ActiveLAMP\Taxonomy\Entity\EntityTermInterface;
use ActiveLAMP\Taxonomy\Taxonomy\TaxonomyServiceInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 *
 */
class RelatedEntities implements EventSubscriber
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
            Events::postLoad
        );
    }

    /**
     *
     * Loads the appropriate entity object given the entity-type and identifier in an EntityTerm object.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        $entityTerm = $eventArgs->getEntity();

        if ($entityTerm instanceof EntityTermInterface) {

            $metadata = $this->service->getMetadata()->getEntityMetadata($entityTerm->getEntityType());
            $entity = $eventArgs->getEntityManager()
                      ->find($metadata->getReflectionClass()->getName(), $entityTerm->getEntityIdentifier());
            if ($entity) {
                $entityTerm->setEntity($entity);
            }
        }
    }
}
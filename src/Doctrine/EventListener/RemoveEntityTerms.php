<?php

namespace ActiveLAMP\Taxonomy\Doctrine\EventListener;

use ActiveLAMP\Taxonomy\Taxonomy\TaxonomyServiceInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
class RemoveEntityTerms implements EventSubscriber
{

    /**
     * @var TaxonomyServiceInterface
     */
    protected $service;

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
            Events::preRemove,
        );
    }

    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();

        if (!$this->service->getMetadata()->hasEntityMetadata($entity)) {
            return;
        }

        $metadata = $this->service->getMetadata()->getEntityMetadata($entity);

        $type = $metadata->getType();
        $id = $metadata->extractIdentifier($entity);

        $dql =
            $eventArgs
                ->getEntityManager()
                ->createQueryBuilder()
                ->delete('ALTaxonomyBundle:EntityTerm', 'et')
                ->andWhere('et.entityType = :type')
                ->andWhere('et.entityIdentifier = :id')
                ->setParameters(array(
                    'type' => $type,
                    'id' => $id,
                ));

        $dql->getQuery()->execute();
    }
}
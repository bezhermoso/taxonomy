<?php

namespace ActiveLAMP\Taxonomy\Doctrine\EventListener;

use ActiveLAMP\Taxonomy\Taxonomy\TaxonomyServiceInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Events;


/**
 * WIP
 *
 * @author Bez Hermoso <bez@activelamp.com>
 */
class PersistTaxonomies implements EventSubscriber
{
    protected $service;

    public function __construct(TaxonomyServiceInterface $taxonomyService)
    {
        $this->service = $taxonomyService;
    }
    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::preFlush,
        );
    }

    public function preFlush(PreFlushEventArgs $eventArgs)
    {
        $identityMap = $eventArgs->getEntityManager()->getUnitOfWork()->getIdentityMap();
        foreach ($identityMap as $class => $entity) {
            var_dump($entity);
        }

        $entity = $eventArgs->getEntity();

        if ($this->service->getMetadata()->hasEntityMetadata($entity)) {
            $this->service->saveTaxonomies($entity);
        }
    }
}

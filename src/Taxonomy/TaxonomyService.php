<?php

namespace ActiveLAMP\Taxonomy\Taxonomy;

use ActiveLAMP\Taxonomy\Doctrine\EventListener\LoadVocabularyFields;
use ActiveLAMP\Taxonomy\Doctrine\EventListener\PrepareEntityTerms;
use ActiveLAMP\Taxonomy\Doctrine\EventListener\RelatedEntities;
use ActiveLAMP\Taxonomy\Doctrine\EventListener\RemoveEntityTerms;
use ActiveLAMP\Taxonomy\Entity\EntityTerm;
use ActiveLAMP\Taxonomy\Entity\EntityTermInterface;
use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
class TaxonomyService extends AbstractTaxonomyService
{
    protected $subscribers;

    public function __construct(ObjectManager $manager)
    {
        /** @var $manager EntityManager */
        if (!$manager instanceof EntityManager) {
            throw new \InvalidArgumentException(sprintf('%s requires Doctrine\\ORM\\EntityManager.', __CLASS__));
        }

        parent::__construct($manager);

        $this->subscribers = array(
            new LoadVocabularyFields($this),
            new PrepareEntityTerms($this),
            new RelatedEntities($this),
            new RemoveEntityTerms($this),
        );

        $eventManager = $manager->getEventManager();

        foreach ($this->subscribers as $subscriber) {
            $eventManager->addEventSubscriber($subscriber);
        }
    }

    /**
     * @param EntityTermInterface $entityTerm
     * @param bool $flush
     */
    public function saveEntityTerm(EntityTermInterface $entityTerm, $flush = true)
    {
        //Injection of entity-type and entity-identifier is done with PrepareEntityTerms subscriber.
        $this->em->persist($entityTerm);

        if ($flush === true) {
            $this->em->flush();
        }
    }

    public function detach()
    {
        /** @var $events EventManager */
        $events = $this->em->getEventManager();

        foreach ($this->subscribers as $subscriber) {
            $events->removeEventSubscriber($subscriber);
        }
    }
}
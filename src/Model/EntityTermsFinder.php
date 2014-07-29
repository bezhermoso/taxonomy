<?php

namespace ActiveLAMP\Taxonomy\Model;

use ActiveLAMP\Taxonomy\Entity\EntityTermInterface;
use ActiveLAMP\Taxonomy\Entity\VocabularyInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

/**
 * Class EntityTermsFinder
 *
 * @author Bez Hermoso <bez@activelamp.com>
 */
class EntityTermsFinder
{
    protected $vocabulary;

    protected $type;

    protected $identifier;

    protected $em;

    protected $entityTermClass;

    /**
     * @param ObjectManager $manager
     * @param VocabularyInterface $vocabulary
     * @param $type
     * @param $identifier
     */
    public function __construct(ObjectManager $manager, VocabularyInterface $vocabulary, $entityTermClass, $type, $identifier)
    {
        $this->em = $manager;
        $this->vocabulary = $vocabulary;
        $this->type = $type;
        $this->identifier = $identifier;
        $this->entityTermClass = $entityTermClass;
    }

    /**
     * @return array|EntityTermInterface[]
     */
    public function find()
    {
        $eTerms = $this->em
            ->getRepository($this->entityTermClass)
            ->createQueryBuilder('et')
            ->innerJoin('et.term', 't')
            ->innerJoin('t.vocabulary', 'v')
            ->addSelect('t')
            ->andWhere('v.id = :vid')
            ->andWhere('et.entityType = :type')
            ->andWhere('et.entityIdentifier = :id')
            ->setParameters(array(
                'vid' => $this->vocabulary->getId(),
                'id' => $this->identifier,
                'type' => $this->type,
            ))->getQuery()->getResult();

        return $eTerms;
    }

    /**
     * @return EntityTermInterface[]
     */
    public function findOne()
    {
        $eTerm = $this->em
            ->getRepository($this->entityTermClass)
            ->createQueryBuilder('et')
            ->innerJoin('et.term', 't')
            ->innerJoin('t.vocabulary', 'v')
            ->addSelect('t')
            ->andWhere('v.id = :vid')
            ->andWhere('et.entityType = :type')
            ->andWhere('et.entityIdentifier = :id')
            ->setParameters(array(
                'vid' => $this->vocabulary->getId(),
                'id' => $this->identifier,
                'type' => $this->type,
            ))
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();

        return $eTerm;
    }
}
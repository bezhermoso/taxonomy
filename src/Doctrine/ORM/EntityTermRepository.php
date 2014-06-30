<?php


namespace ActiveLAMP\Taxonomy\Doctrine\ORM;


use ActiveLAMP\Taxonomy\Entity\EntityTermInterface;
use ActiveLAMP\Taxonomy\Entity\VocabularyInterface;
use ActiveLAMP\Taxonomy\Model\EntityTermRepositoryInterface;
use Doctrine\ORM\EntityRepository;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
class EntityTermRepository extends EntityRepository implements EntityTermRepositoryInterface
{

    /**
     * @param string|VocabularyInterface $vocabulary
     * @param string $entityType
     * @param string $entityIdentifier
     * @return EntityTermInterface[]
     */
    public function findEntities($vocabulary, $entityType, $entityIdentifier)
    {
        $eTerms =
            $this
                ->createQueryBuilder('et')
                ->innerJoin('et.term', 't')
                ->innerJoin('t.vocabulary', 'v')
                ->addSelect('t')
                ->andWhere('v.id = :vid')
                ->andWhere('et.entityType = :type')
                ->andWhere('et.entityIdentifier = :id')
                ->setParameters(array(
                    'vid' => $vocabulary,
                    'id' => $entityIdentifier,
                    'type' => $entityType,
                ))->getQuery()->getResult();

        return $eTerms;
    }

    /**
     * @param string|VocabularyInterface $vocabulary
     * @param string $entityType
     * @param string $entityIdentifier
     * @return EntityTermInterface
     */
    public function findEntity($vocabulary, $entityType, $entityIdentifier)
    {
        $eTerm =
            $this
                ->createQueryBuilder('et')
                ->innerJoin('et.term', 't')
                ->innerJoin('t.vocabulary', 'v')
                ->addSelect('t')
                ->andWhere('v.id = :vid')
                ->andWhere('et.entityType = :type')
                ->andWhere('et.entityIdentifier = :id')
                ->setParameters(array(
                    'vid' => $vocabulary,
                    'id' => $entityIdentifier,
                    'type' => $entityType,
                ))
                ->setMaxResults(1)
                ->getQuery()->getOneOrNullResult();

        return $eTerm;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return parent::getClassName();
    }
}
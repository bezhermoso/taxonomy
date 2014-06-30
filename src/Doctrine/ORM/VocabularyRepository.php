<?php
/**
 * Created by PhpStorm.
 * User: bezalelhermoso
 * Date: 6/4/14
 * Time: 10:30 AM
 */

namespace ActiveLAMP\Taxonomy\Entity\Repository;

use ActiveLAMP\Taxonomy\Entity\VocabularyInterface;
use ActiveLAMP\Taxonomy\Model\VocabularyRepositoryInterface;
use Doctrine\ORM\EntityRepository;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
class VocabularyRepository extends EntityRepository implements VocabularyRepositoryInterface
{

    /**
     * @param $id
     * @return VocabularyInterface
     */
    public function findById($id)
    {
        return $this->find($id);
    }

    /**
     * @param $name
     * @return VocabularyInterface
     */
    public function findByName($name)
    {
        return $this->findOneBy(array('name' => $name));
    }
}
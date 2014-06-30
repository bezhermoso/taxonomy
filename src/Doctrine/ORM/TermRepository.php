<?php
/**
 * Created by PhpStorm.
 * User: bezalelhermoso
 * Date: 6/4/14
 * Time: 10:31 AM
 */

namespace ActiveLAMP\Taxonomy\Entity\Repository;


use ActiveLAMP\Taxonomy\Entity\TermInterface;
use ActiveLAMP\Taxonomy\Model\TermRepositoryInterface;
use Doctrine\ORM\EntityRepository;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
class TermRepository extends EntityRepository implements TermRepositoryInterface
{

    /**
     * @param $id
     * @return TermInterface
     */
    public function findById($id)
    {
        return $this->find($id);
    }

    /**
     * @param $name
     * @return TermInterface
     */
    public function findByName($name)
    {
        return $this->findOneBy(array(
            'name' => $name,
        ));
    }

    /**
     * @param $vocabulary
     * @return TermInterface[]
     */
    public function findByVocabulary($vocabulary)
    {
        return $this->findBy(array(
            'vocabulary' => $vocabulary,
        ), array('weight' => 'desc'));
    }
}
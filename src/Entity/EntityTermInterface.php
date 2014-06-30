<?php
/**
 * Created by PhpStorm.
 * User: bezalelhermoso
 * Date: 6/30/14
 * Time: 3:19 PM
 */

namespace ActiveLAMP\Taxonomy\Entity;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
interface EntityTermInterface
{
    /**
     * @param $type
     * @return void
     */
    public function setEntityType($type);

    /**
     * @return string
     */
    public function getEntityType();

    /**
     * @param int $id
     * @return void
     */
    public function setEntityIdentifier($id);

    /**
     * @return int
     */
    public function getEntityIdentifier();

    /**
     * @param $entity
     * @return void
     */
    public function setEntity($entity);

    /**
     * @return mixed
     */
    public function getEntity();

    /**
     * @return TermInterface
     */
    public function getTerm();

    /**
     * @param TermInterface $term
     * @return void
     */
    public function setTerm(TermInterface $term);
} 
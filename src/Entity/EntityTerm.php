<?php

namespace ActiveLAMP\Taxonomy\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
abstract class EntityTerm implements EntityTermInterface
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var TermInterface
     */
    protected $term;

    /**
     * @var string
     */
    protected $entityType;

    /**
     * @var int
     */
    protected $entityIdentifier;

    /**
     * @var mixed
     */
    protected $entity;

    /**
     * @param mixed $type
     */
    public function setEntityType($type)
    {
        $this->entityType = $type;
    }

    /**
     * @return string
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * @param int $id
     */
    public function setEntityIdentifier($id)
    {
        $this->entityIdentifier = $id;
    }

    /**
     * @return int
     */
    public function getEntityIdentifier()
    {
        return $this->entityIdentifier;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return TermInterface
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * @param TermInterface $term
     */
    public function setTerm(TermInterface $term)
    {
        $this->term = $term;
    }
}
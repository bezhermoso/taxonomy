<?php

namespace ActiveLAMP\Taxonomy\Metadata;
use Traversable;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
class TaxonomyMetadata implements \Countable, \IteratorAggregate
{
    /**
     * @var array|Entity[]
     */
    protected $entityMetadata = array();

    public function addEntityMetadata(Entity $entity)
    {
        if ($this->hasEntityMetadata($entity->getReflectionClass()->getName())) {
            return;
        }

        $this->entityMetadata[] = $entity;
    }

    /**
     * @param $object
     * @throws \InvalidArgumentException
     * @return Entity
     */
    public function getEntityMetadata($object)
    {
        $haystack = $object;

        if (is_object($object)) {
            $haystack = get_class($object);
        }

        if (!$this->hasEntityMetadata($haystack)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a recognized taxonomy entity.', $haystack));
        }

        $metadata = null;
        foreach ($this->entityMetadata as $entity) {
            if ($entity->getReflectionClass()->getName() === $haystack) {
                return $entity;
            }
        }

        return null;
    }

    public function hasEntityMetadata($object)
    {
        $haystack = $object;

        if (is_object($object)) {
            $haystack = get_class($object);
        }

        foreach ($this->entityMetadata as $entity) {
            if ($entity->getReflectionClass()->getName() === $haystack) {
                return true;
            }
        }

        return false;
    }

    public function getAllEntityMetadata()
    {
        return $this->entityMetadata;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->entityMetadata);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->entityMetadata);
    }
}
<?php

namespace ActiveLAMP\Taxonomy\Collection;

use ActiveLAMP\Taxonomy\Iterator\InnerEntityIterator;
use Traversable;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
class RelatedEntityCollection implements \IteratorAggregate
{
    protected $items;

    /**
     * An array|iterator of EntityTerm instances.
     *
     * @param $entityTerms
     * @throws \InvalidArgumentException
     */
    public function __construct(array $entityTerms)
    {
        $this->items = $entityTerms;
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
        return new InnerEntityIterator(new \ArrayIterator($this->items));
    }

    /**
     *
     * @param $entityTerms
     * @throws \InvalidArgumentException
     * @return RelatedEntityCollection
     */
    public static function create($entityTerms)
    {
        if (is_array($entityTerms)) {
            return new self($entityTerms);
        } elseif ($entityTerms instanceof \Iterator || $entityTerms instanceof \IteratorAggregate) {
            return new self(iterator_to_array($entityTerms));
        } else {
            throw new \InvalidArgumentException(
                sprintf(
                    'Cannot create an appropriate iterable object: expects an array or Traversable. "%s" given.',
                    is_object($entityTerms) ? get_class($entityTerms) : gettype($entityTerms)
                ));
        }
    }
}
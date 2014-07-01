<?php

namespace ActiveLAMP\Taxonomy\Metadata;

use Doctrine\Common\Collections\ArrayCollection;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
class Vocabulary 
{
    /**
     * @var \ReflectionProperty
     */
    protected $property;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $singular = false;

    /**
     * @param \ReflectionProperty $property
     * @param $name
     * @param bool $singular
     */
    public function __construct(\ReflectionProperty $property, $name, $singular)
    {
        $this->property = $property;
        $this->name = $name;
        $this->singular = $singular;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPropertyName()
    {
        return $this->property->getName();
    }

    public function isSingular()
    {
        return (boolean) $this->singular;
    }

    public function getReflectionProperty()
    {
        return $this->property;
    }

    /**
     * @param $entity
     * @return \ActiveLAMP\Taxonomy\Entity\PluralVocabularyField|ArrayCollection
     */
    public function extractValueInField($entity)
    {
        $this->property->setAccessible(true);
        $field = $this->property->getValue($entity);
        $this->property->setAccessible(false);

        return $field;
    }

    public function setVocabularyField($field, $entity)
    {
        $this->property->setAccessible(true);
        $this->property->setValue($entity, $field);
        $this->property->setAccessible(false);
    }
} 
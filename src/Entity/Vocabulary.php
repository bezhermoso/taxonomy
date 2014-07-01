<?php

namespace ActiveLAMP\Taxonomy\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Bez Hermoso <bez@activelamp.com>
 * @author Tom Friedhof <tom@activelamp.com>
 */
abstract class Vocabulary implements VocabularyInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var ArrayCollection|TermInterface[]
     */
    protected $terms;

    public function __construct()
    {
        $this->terms = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param string $labelName
     * @return void
     */
    public function setLabel($labelName)
    {
        $this->label = $labelName;
    }

    /**
     * Get labelName
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->getName();
    }

    /**
     * @param $name
     * @throws \DomainException
     * @return TermInterface
     */
    public function getTermByName($name)
    {
        /** @var $term Term */
        foreach ($this->terms as $term) {
            if ($term->getName() == $name) {
                return $term;
            }
        }

        throw new \DomainException(sprintf('Cannot find term of name "%s".', $name));
    }

    /**
     * @param TermInterface $term
     */
    public function addTerm(TermInterface $term)
    {
        if (!$this->terms->contains($term)) {
            $this->terms->add($term);
        }
    }
}
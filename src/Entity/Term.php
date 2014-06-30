<?php

namespace ActiveLAMP\Taxonomy\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 * @author Tom Friedhof <tom@activelamp.com>
 */
abstract class Term implements TermInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var VocabularyInterface
     */
    protected $vocabulary;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var int
     */
    protected $weight;

    /**
     * @var EntityTermInterface[]
     */
    protected $entityTerms;


    public function __construct()
    {
        $this->entityTerms = new ArrayCollection();
        $this->weight = 0;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
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
     * @param int $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * Get weight
     *
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param VocabularyInterface $vocabulary
     * @return void
     */
    public function setVocabulary(VocabularyInterface $vocabulary = null)
    {
        $this->vocabulary = $vocabulary;

        if ($vocabulary !== null) {
            $vocabulary->addTerm($this);
        }
    }

    /**
     * Get vocabulary
     *
     * @return VocabularyInterface
     */
    public function getVocabulary()
    {
        return $this->vocabulary;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return void
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->name;
    }
}
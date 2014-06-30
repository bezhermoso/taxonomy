<?php
/**
 * Created by PhpStorm.
 * User: bezalelhermoso
 * Date: 6/30/14
 * Time: 3:19 PM
 */

namespace ActiveLAMP\Taxonomy\Entity;
use ActiveLAMP\Taxonomy\Entity\Term;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
interface TermInterface
{
    /**
     * Get id
     *
     * @return int
     */
    public function getId();

    /**
     * Set name
     *
     * @param string $name
     * @return void
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Set weight
     *
     * @param integer $weight
     */
    public function setWeight($weight);

    /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight();

    /**
     * @param VocabularyInterface $vocabulary
     * @return void
     */
    public function setVocabulary(VocabularyInterface $vocabulary = null);

    /**
     * Get vocabulary
     *
     * @return \ActiveLAMP\Taxonomy\Entity\Vocabulary
     */
    public function getVocabulary();

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param string $label
     * @return void
     */
    public function setLabel($label);

    /**
     * @return string
     */
    function __toString();
}
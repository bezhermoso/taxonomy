<?php
/**
 * Created by PhpStorm.
 * User: bezalelhermoso
 * Date: 6/30/14
 * Time: 3:18 PM
 */

namespace ActiveLAMP\Taxonomy\Entity;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
interface VocabularyInterface 
{
    /**
     * @return int
     */
    public function getId();

    /**
     * Set labelName
     *
     * @param string $labelName
     * @return void
     */
    public function setLabel($labelName);

    /**
     * Get labelName
     *
     * @return string
     */
    public function getLabel();

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
     * Set description
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description);

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription();

    /**
     * @param $name
     * @throws \DomainException
     * @return TermInterface
     */
    public function getTermByName($name);

    /**
     * @param TermInterface $term
     */
    public function addTerm(TermInterface $term);

    /**
     * @return string
     */
    public function __toString();

} 
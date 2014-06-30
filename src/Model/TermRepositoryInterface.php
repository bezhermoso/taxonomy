<?php

namespace ActiveLAMP\Taxonomy\Model;

use ActiveLAMP\Taxonomy\Entity\TermInterface;

/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
interface TermRepositoryInterface
{
    /**
     * @param $id
     * @return TermInterface
     */
    public function findById($id);

    /**
     * @param $name
     * @return TermInterface
     */
    public function findByName($name);

    /**
     * @param $vocabulary
     * @return TermInterface[]
     */
    public function findByVocabulary($vocabulary);

    /**
     * @return TermInterface[]
     */
    public function findAll();
}
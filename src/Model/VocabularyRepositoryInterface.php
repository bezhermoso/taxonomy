<?php

namespace ActiveLAMP\Taxonomy\Model;

use ActiveLAMP\Taxonomy\Entity\VocabularyInterface;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
interface VocabularyRepositoryInterface
{
    /**
     * @param $id
     * @return VocabularyInterface
     */
    public function findById($id);

    /**
     * @param $name
     * @return VocabularyInterface
     */
    public function findByName($name);

    /**
     * @return VocabularyInterface[]
     */
    public function findAll();
}
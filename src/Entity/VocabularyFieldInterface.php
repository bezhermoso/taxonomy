<?php

namespace ActiveLAMP\Taxonomy\Entity;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
interface VocabularyFieldInterface
{
    /**
     * @return array|EntityTermInterface[]
     */
    public function getInsertDiff();

    /**
     * @return array|EntityTermInterface[]
     */
    public function getDeleteDiff();

    /**
     * @return VocabularyInterface
     */
    public function getVocabulary();

    /**
     * @return boolean
     */
    public function isDirty();

    /**
     * @param boolean $dirty
     * @return void
     */
    public function setDirty($dirty);
}
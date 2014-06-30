<?php

namespace ActiveLAMP\Taxonomy\Annotations;

use Doctrine\Common\Annotations\Annotation;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 *
 * @Annotation
 */
class Taxonomy extends Annotation
{
    protected $vocabularies = array();

    /**
     * @return array
     */
    public function getVocabularies()
    {
        return $this->vocabularies;
    }
}
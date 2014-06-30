<?php

namespace ActiveLAMP\Taxonomy\Annotations;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 * @Annotation
 */
class Entity
{
    public $type = null;

    public $identifier = 'id';

    public function getType()
    {
        return $this->type;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }
}
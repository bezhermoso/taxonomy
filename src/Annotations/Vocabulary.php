<?php

namespace ActiveLAMP\Taxonomy\Annotations;

use Doctrine\Common\Annotations\Annotation;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 *
 * @Annotation
 *
 */
class Vocabulary extends Annotation
{
    protected $name = null;

    protected $columnName = null;

    protected $singular = false;

    public function getName()
    {
        return $this->name;
    }

    public function getColumnName()
    {
        if ($this->columnName === null) {
            $this->columnName = 'taxonomy_' . $this->name;
        }

        return $this->columnName;
    }

    public function isSingular()
    {
        return (boolean) $this->singular;
    }
} 
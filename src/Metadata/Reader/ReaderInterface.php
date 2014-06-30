<?php

namespace ActiveLAMP\Taxonomy\Metadata\Reader;
use ActiveLAMP\Taxonomy\Metadata\Entity;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
interface ReaderInterface 
{
    public function loadMetadataForClass($className, Entity $metadata);
}
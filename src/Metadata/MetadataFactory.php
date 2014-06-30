<?php

namespace ActiveLAMP\Taxonomy\Metadata;

use ActiveLAMP\Taxonomy\Metadata\Reader\AnnotationReader;
use ActiveLAMP\Taxonomy\Metadata\Reader\ReaderInterface;
use ActiveLAMP\Taxonomy\Metadata\Reader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;


/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
class MetadataFactory 
{

    /**
     * @var TaxonomyMetadata
     */
    protected $metadata;

    /**
     * @var \ActiveLAMP\Taxonomy\Metadata\Reader\ReaderInterface
     */
    protected $reader;

    /**
     * @param ReaderInterface $reader
     */
    public function __construct(ReaderInterface $reader = null)
    {
        if ($reader === null) {
            $reader = new AnnotationReader();
        }
        $this->reader = $reader;
    }

    /**
     * @param EntityManager $em
     * @return TaxonomyMetadata
     */
    public function getMetadata(EntityManager $em)
    {
        $doctrineMetadata = $em->getMetadataFactory()->getAllMetadata();

        $taxMetadata = new TaxonomyMetadata();

        foreach ($doctrineMetadata as $dm) {

            if (!$dm instanceof ClassMetadata) {
                continue;
            }

            $m = $this->createEntityMetadataForClass($dm->getReflectionClass());

            $this->reader->loadMetadataForClass($dm->getReflectionClass()->getName(), $m);

            $prevParent = $m;

            foreach ($this->getClassParents($dm->getReflectionClass()) as $parent) {
                $pm = $this->createEntityMetadataForClass($parent);
                $this->reader->loadMetadataForClass($parent->getName(), $pm);
                $prevParent->setParent($pm);
                $prevParent = $pm;
            }

            if ($m->getType() && count($m->getVocabularies()) > 0) {
                $taxMetadata->addEntityMetadata($m);
            }
        }

        return $taxMetadata;
    }

    /**
     * @param \ReflectionClass $class
     * @return \ReflectionClass[]
     */
    public function getClassParents(\ReflectionClass $class)
    {
        if ($class->getParentClass()) {
            $parents = $this->getClassParents($class->getParentClass());
            $p = array($class->getParentClass());
            return array_merge($p, $parents);
        } else {
            return array();
        }
    }

    /**
     * @param \ReflectionClass $class
     * @return Entity
     */
    public function createEntityMetadataForClass(\ReflectionClass $class)
    {
        $m = new Entity($class);
        return $m;
    }
}
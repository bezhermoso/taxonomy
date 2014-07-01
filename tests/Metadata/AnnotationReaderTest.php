<?php


namespace ActiveLAMP\Taxonomy\Tests\Metadata;

use ActiveLAMP\Taxonomy\Metadata\MetadataFactory;
use ActiveLAMP\Taxonomy\Tests\TestCase;

class AnnotationReaderTest extends TestCase
{
    protected $service;

    protected $_em;

    public function setUp()
    {
        $em = static::getEntityManager();
        $this->_em = $em;
    }

    public function testMetadata()
    {
        $factory = new MetadataFactory();
        $metadata = $factory->getMetadata($this->_em);

        $this->assertCount(2, $metadata);

        $this->assertTrue($metadata->hasEntityMetadata('ActiveLAMP\\Taxonomy\\Tests\\Fixtures\\ORM\\Post'));
        $this->assertTrue($metadata->hasEntityMetadata('ActiveLAMP\\Taxonomy\\Tests\\Fixtures\\ORM\\UserRole'));
    }

    /**
     * @dataProvider dataTestVocabularyMetadataProvider
     */
    public function testVocabularyMetadata($class, $expectations)
    {
        $factory = new MetadataFactory();
        $metadata = $factory->getMetadata($this->_em);

        if ($expectations['exists'] === true) {

            $this->assertTrue($metadata->hasEntityMetadata($class));
            $entityMetadata = $metadata->getEntityMetadata($class);

            $vocabularies = $entityMetadata->getVocabularies();
            $this->assertCount(count($expectations['vocabularies']), $vocabularies);

            foreach ($expectations['vocabularies'] as $name => $expectedVoc) {
                $vocab = $entityMetadata->getVocabularyByName($name);
                $this->assertNotNull($vocab);
                $this->assertTrue($vocab->isSingular() === $expectedVoc['singular']);
                $this->assertEquals($expectedVoc['property'], $vocab->getPropertyName());
            }

            foreach ($expectations['non_vocabularies'] as $nonVocab) {
                $this->assertNull($entityMetadata->getVocabularyByName($nonVocab));
            }

        } else {
            $this->assertFalse($metadata->hasEntityMetadata($class));
        }
    }

    public function dataTestVocabularyMetadataProvider()
    {
        return array(
            array(
                'ActiveLAMP\\Taxonomy\\Tests\\Fixtures\\ORM\\Post',
                array(
                    'exists' => true,
                    'vocabularies' => array(
                        'category' => array(
                            'singular' => true,
                            'property' => 'category',
                        ),
                    ),
                    'non_vocabularies' => array(
                        'foo', 'bar'
                    )
                ),
            ),
            array(
                'ActiveLAMP\\Taxonomy\\Tests\\Fixtures\\ORM\\UserRole',
                array(
                    'exists' => true,
                    'count' => 1,
                    'singular' => false,
                    'vocabularies' => array(
                        'role' => array(
                            'singular' => false,
                            'property' => 'roles',
                        ),
                    ),
                    'non_vocabularies' => array(
                        'foo',
                    )
                ),
            ),
            array(
                'ActiveLAMP\\Taxonomy\\Tests\\Fixtures\\ORM\\NothingToSeeHere',
                array(
                    'exists' => false,
                ),
            ),
        );
    }
} 
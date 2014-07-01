<?php


namespace ActiveLAMP\Taxonomy\Tests\Listener;
use ActiveLAMP\Taxonomy\Taxonomy\TaxonomyService;
use ActiveLAMP\Taxonomy\Tests\TestCase;
use Doctrine\ORM\EntityManager;


/**
 * Class LoadVocabularyFieldsTest
 *
 * @author Bez Hermoso <bez@activelamp.com>
 */
class LoadVocabularyFieldsTest extends TestCase
{
    /**
     * @var EntityManager
     */
    protected $_em;

    /**
     * @var TaxonomyService
     */
    protected $service;

    public function setUp()
    {
        $this->_em = static::createEntityManager();
        $this->service = static::createTaxonomyService($this->_em);
        //$this->_em->beginTransaction();
    }

    public function testSingular()
    {
        $postRepo = $this->_em->getRepository('Test:Post');
        $post = $postRepo->findOneBy(array('slug' => 'hello-world'));

        $category = $post->getCategory();

        $this->assertInstanceOf('ActiveLAMP\\Taxonomy\\Entity\\VocabularyFieldInterface', $category);
        $this->assertInstanceOf('ActiveLAMP\\Taxonomy\\Entity\\SingularVocabularyField', $category);

        $this->assertEquals('Software', $category->getLabel());
        $this->assertEquals('software', $category->getName());

        $this->assertInstanceOf('ActiveLAMP\\Taxonomy\\Entity\\VocabularyInterface', $category->getVocabulary());
        $this->assertInstanceOf('ActiveLAMP\\Taxonomy\\Tests\\Fixtures\\ORM\\Vocabulary', $category->getVocabulary());

        $this->assertEquals('category', $category->getVocabulary());

        $post2 = $postRepo->findOneBy(array('slug' => 'test123'));

        $category = $post2->getCategory();
        $this->assertInstanceOf('ActiveLAMP\\Taxonomy\\Entity\\VocabularyFieldInterface', $category);
        $this->assertInstanceOf('ActiveLAMP\\Taxonomy\\Entity\\SingularVocabularyField', $category);

        $this->assertNull($category->getLabel());
        $this->assertNull($category->getName());
        $this->assertEquals('category', $category->getVocabulary());

    }

    public function testPlural()
    {
        $userRoleRepo = $this->_em->getRepository('Test:UserRole');

        $userRole = $userRoleRepo->findOneBy(array('id' => 1));

        $roles = $userRole->getRoles();

        $this->assertInstanceOf('ActiveLAMP\\Taxonomy\\Entity\\VocabularyFieldInterface', $roles);
        $this->assertInstanceOf('ActiveLAMP\\Taxonomy\\Entity\\PluralVocabularyField', $roles);

        $this->assertInstanceOf('ActiveLAMP\\Taxonomy\\Entity\\VocabularyInterface', $roles->getVocabulary());
        $this->assertInstanceOf('ActiveLAMP\\Taxonomy\\Tests\\Fixtures\\ORM\\Vocabulary', $roles->getVocabulary());
        $this->assertCount(2, $roles);

        $userRole = $userRoleRepo->findOneBy(array('id' => 2));

        foreach ($roles as $role) {
            $this->assertInstanceOf('ActiveLAMP\\Taxonomy\\Entity\\TermInterface', $role);
            $this->assertInstanceOf('ActiveLAMP\\Taxonomy\\Tests\\Fixtures\\ORM\\Term', $role);
        }

        $this->assertEquals(
            array(
                $this->service->findTermByName('user'),
                $this->service->findTermByName('mod')
            ),
            $roles->toArray()
        );

        $roles = $userRole->getRoles();

        $this->assertInstanceOf('ActiveLAMP\\Taxonomy\\Entity\\VocabularyFieldInterface', $roles);
        $this->assertInstanceOf('ActiveLAMP\\Taxonomy\\Entity\\PluralVocabularyField', $roles);

        $this->assertInstanceOf('ActiveLAMP\\Taxonomy\\Entity\\VocabularyInterface', $roles->getVocabulary());
        $this->assertInstanceOf('ActiveLAMP\\Taxonomy\\Tests\\Fixtures\\ORM\\Vocabulary', $roles->getVocabulary());
        $this->assertCount(1, $roles);

        $this->assertEquals(
            array(
                $this->service->findTermByName('super_admin')
            ),
            $roles->toArray()
        );
    }

    public function tearDown()
    {
        //$this->service->detach();
        //$this->_em->rollback();
    }
} 
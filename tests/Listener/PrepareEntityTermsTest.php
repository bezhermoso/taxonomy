<?php


namespace ActiveLAMP\Taxonomy\Tests\Listener;
use ActiveLAMP\Taxonomy\Doctrine\EventListener\PrepareEntityTerms;
use ActiveLAMP\Taxonomy\Taxonomy\TaxonomyService;
use ActiveLAMP\Taxonomy\Tests\Fixtures\ORM\EntityTerm;
use ActiveLAMP\Taxonomy\Tests\Fixtures\ORM\Foo;
use ActiveLAMP\Taxonomy\Tests\Fixtures\ORM\Post;
use ActiveLAMP\Taxonomy\Tests\TestCase;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;


/**
 * Class PrepareEntityTermsTest
 *
 * @author Bez Hermoso <bez@activelamp.com>
 */
class PrepareEntityTermsTest extends TestCase
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
        $this->_em->beginTransaction();
    }

    public function testInjection()
    {
        $post = new Post();
        $post->setTitle('Foo');
        $post->setBody('Bar');
        $post->setSlug('foo');

        $this->_em->persist($post);
        $this->_em->flush();

        $webDev = $this->service->findTermByName('web_dev');

        $entityTerm = new EntityTerm();
        $entityTerm->setTerm($webDev);
        $entityTerm->setEntity($post);

        $this->_em->persist($entityTerm);
        $this->_em->flush();

        $this->assertEquals($post->getId(), $entityTerm->getEntityIdentifier());
        $this->assertEquals(get_class($post), $entityTerm->getEntityType());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInjectionOfNonManagedEntity()
    {
        $foo = new Foo();
        $this->_em->persist($foo);
        $this->_em->flush();

        $webDev = $this->service->findTermByName('web_dev');

        $entityTerm = new EntityTerm();
        $entityTerm->setTerm($webDev);
        $entityTerm->setEntity($foo);

        $this->_em->persist($entityTerm);
        $this->_em->flush();
    }

    /**
     * @expectedException \Exception
     */
    public function testInjectionWithMissingRelations()
    {
        $post = new Post();
        $post->setTitle('Foo');
        $post->setBody('Bar');
        $post->setSlug('foo-2');

        $this->_em->persist($post);
        $this->_em->flush();

        $webDev = $this->service->findTermByName('web_dev');
        $entityTerm = new EntityTerm();
        $entityTerm->setEntity($webDev);
        $this->_em->persist($entityTerm);
        $this->_em->flush();
    }

    public function tearDown()
    {
        $this->_em->rollback();
    }
}
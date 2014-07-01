<?php


namespace ActiveLAMP\Taxonomy\Tests\Fixtures\DataFixtures;
use ActiveLAMP\Taxonomy\Tests\Fixtures\ORM\EntityTerm;
use ActiveLAMP\Taxonomy\Tests\Fixtures\ORM\Post;
use ActiveLAMP\Taxonomy\Tests\Fixtures\ORM\UserRole;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


/**
 * Class PrepareEntityTaxonomies
 *
 * @author Bez Hermoso <bez@activelamp.com>
 */
class PrepareEntityTaxonomies extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadPosts($manager);
        $this->loadUserRoles($manager);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadPosts(ObjectManager $manager)
    {
        $software = $this->getReference('term-software');

        $post = new Post();
        $post->setBody('Test.');
        $post->setTitle('Hello, world!');
        $post->setSlug('hello-world');

        $manager->persist($post);
        $manager->flush();

        $entityTerm = new EntityTerm();
        $entityTerm->setEntityType(get_class($post));
        $entityTerm->setEntityIdentifier($post->getId());
        $entityTerm->setTerm($software);

        $manager->persist($entityTerm);
        $manager->flush();


        $post = new Post();
        $post->setBody('Test.');
        $post->setTitle('Test article');
        $post->setSlug('test123');

        $manager->persist($post);
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadUserRoles(ObjectManager $manager)
    {
        $roles = $this->getReference('vocab-role');

        $userRole = new UserRole();
        $manager->persist($userRole);
        $manager->flush($userRole);

        foreach (array('user', 'mod') as $r) {
            $eTerm = new EntityTerm();
            $eTerm->setEntityType(get_class($userRole));
            $eTerm->setEntityIdentifier($userRole->getId());
            $eTerm->setTerm($roles->getTermByName($r));
            $manager->persist($eTerm);
        }
        $manager->flush();

        $userRole = new UserRole();
        $manager->persist($userRole);
        $manager->flush($userRole);

        foreach (array('super_admin') as $r) {
            $eTerm = new EntityTerm();
            $eTerm->setEntityType(get_class($userRole));
            $eTerm->setEntityIdentifier($userRole->getId());
            $eTerm->setTerm($roles->getTermByName($r));
            $manager->persist($eTerm);
        }
        $manager->flush();

    }
}
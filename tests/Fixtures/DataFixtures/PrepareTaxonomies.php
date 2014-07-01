<?php


namespace ActiveLAMP\Taxonomy\Tests\Fixtures\DataFixtures;
use ActiveLAMP\Taxonomy\Tests\Fixtures\ORM\Term;
use ActiveLAMP\Taxonomy\Tests\Fixtures\ORM\Vocabulary;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


/**
 * Class PrepareTaxonomies
 *
 * @author Bez Hermoso <bez@activelamp.com>
 */
class PrepareTaxonomies extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $vocabularies = array(
            'role' => array(
                'label' => 'Role',
                'description' => 'Roles assigned to users to define the permissions granted to them.',
                'terms' => array(
                    'user' => array(
                        'label' => 'User',
                    ),
                    'mod' => array(
                        'label' => 'Moderator',
                    ),
                    'admin' => array(
                        'label' => 'Administrator',
                    ),
                    'super_admin' => array(
                        'label' => 'Super administrator',
                    ),
                ),
            ),
            'category' => array(
                'label' => 'Post categories',
                'description' => 'Groups posts according to general topic.',
                'terms' => array(
                    'software' => array(
                        'label' => 'Software',
                    ),
                    'compsci' => array(
                        'label' => 'Computer science',
                    ),
                    'web_dev' => array(
                        'label' => 'Web development',
                    ),
                ),
            ),
        );

        foreach ($vocabularies as $vname => $vdef) {

            $v = new Vocabulary();
            $v->setName($vname);
            $v->setLabel($vdef['label']);
            $v->setDescription($vdef['description']);
            $this->addReference('vocab-' . $vname, $v);
            $manager->persist($v);

            foreach ($vdef['terms'] as $tname => $tdef) {
                $t = new Term();
                $t->setName($tname);
                $t->setLabel($tdef['label']);
                $t->setVocabulary($v);
                $this->addReference('term-' . $tname, $t);
                $manager->persist($t);
            }
        }

        $manager->flush();

    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}
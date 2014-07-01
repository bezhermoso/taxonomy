<?php

namespace ActiveLAMP\Taxonomy\Tests;

use ActiveLAMP\Taxonomy\Taxonomy\TaxonomyService;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * @author Bez Hermoso <bez@activelamp.com>
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManager
     */
    public static $em;

    /**
     * @return EntityManager
     */
    public static function getEntityManager()
    {
        if (static::$em === null) {
            static::$em = static::createEntityManager();
        }

        return static::$em;
    }

    /**
     * @return EntityManager
     */
    public static function createEntityManager()
    {
        return include __DIR__ . '/config/em.php';
    }

    /**
     * @param ObjectManager $om
     * @return TaxonomyService
     */
    public function createTaxonomyService(ObjectManager $om)
    {
        return new TaxonomyService(
                $om,
                'ActiveLAMP\\Taxonomy\\Tests\\Fixtures\\ORM\\Vocabulary',
                'ActiveLAMP\\Taxonomy\\Tests\\Fixtures\\ORM\\Term',
                'ActiveLAMP\\Taxonomy\\Tests\\Fixtures\\ORM\\EntityTerm'
            );
    }
}
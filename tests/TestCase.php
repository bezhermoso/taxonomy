<?php

namespace ActiveLAMP\Taxonomy\Tests;

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
     * @var ContainerInterface
     */
    public static $container;

    /**
     * @var EntityManager
     */
    public static $em;

    /**
     * @return ContainerInterface
     */
    public static function getContainer()
    {
        if (static::$container === null) {
            $builder = new ContainerBuilder();
            $loader = new YamlFileLoader($builder, new FileLocator(__DIR__ . '/config'));
            $loader->load('services.yml');
            $builder->compile();
            static::$container = $builder;
        }

        return static::$container;
    }

    /**
     * @return EntityManager
     */
    public static function getEntityManager()
    {
        if (static::$em === null) {
            $em = include __DIR__ . '/config/em.php';
            static::$em = $em;
        }

        return static::$em;
    }
}
<?php


use ActiveLAMP\Taxonomy\Tests\TestCase;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\Tools\SchemaTool;

$autoloader = __DIR__.'/../vendor/autoload.php';

if (file_exists($autoloader) && ($loader = include __DIR__.'/../vendor/autoload.php') && class_exists('Doctrine\Common\Annotations\AnnotationRegistry')) {

    AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

    /** @var $em \Doctrine\ORM\EntityManager */
    $em = include __DIR__ . '/config/em.php';
    $tool = new SchemaTool($em);
    $classes = $em->getMetadataFactory()->getAllMetadata();

    try {
        $em->getConnection()->connect();
        $tool->createSchema($classes);
    } catch (\Exception $e) {
        // Not supported by SQLite
        //$tool->updateSchema($classes);
    }

    $purger = new ORMPurger($em);
    $loader = new Loader();
    $loader->loadFromDirectory(__DIR__ . '/Fixtures/DataFixtures');
    $executor = new ORMExecutor($em, $purger);
    $executor->execute($loader->getFixtures());

    TestCase::$em = $em;

} else {
    die('Cannot find dependencies.');
}
<?php


use ActiveLAMP\Taxonomy\Tests\TestCase;
use Doctrine\Common\Annotations\AnnotationRegistry;
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
    } catch (\Exception $e) {
        $tool->createSchema($classes);
    }
    TestCase::$em = $em;
} else {
    die('Cannot find dependencies.');
}
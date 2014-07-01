<?php


use Doctrine\Common\Annotations\AnnotationRegistry;

$autoloader = __DIR__.'/../vendor/autoload.php';

if (file_exists($autoloader) && ($loader = include __DIR__.'/../vendor/autoload.php') && class_exists('Doctrine\Common\Annotations\AnnotationRegistry')) {
    AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
} else {
    die('Cannot find dependencies.');
}
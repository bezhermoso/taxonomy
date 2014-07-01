<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

$conn = array(
    'driver' => 'pdo_sqlite',
    'path' => 'test.db',
);

$config = Setup::createYAMLMetadataConfiguration(array(__DIR__), true);
$em = EntityManager::create($conn, $config);
$config->addEntityNamespace('Test', 'ActiveLAMP\\Taxonomy\\Tests\\Fixtures\\ORM');
return $em;
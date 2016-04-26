<?php

use Composer\Autoload\ClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

$loader->add('Mapbender', __DIR__.'/../mapbender/src');

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;

<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

$loader->add('Mapbender', __DIR__.'/../mapbender/src');
$loader->add('FOM', __DIR__.'/../fom/src');
$loader->add('OwsProxy3', __DIR__.'/../owsproxy/src');

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;

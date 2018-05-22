<?php
/**
 *
 * @author David Patzke <david.patzke@wheregroup.com>
 */

// app/config/routing.php
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$path                    = "/Controller/";

$defaultAnnotationRoutes = [
                            "MapbenderCoreBundle",
                            "MapbenderManagerBundle",
                            "MapbenderWmsBundle",
                            "MapbenderCoordinatesUtilityBundle",
                            "FOMManagerBundle",
                            "FOMUserBundle",
                            "OwsProxy3CoreBundle"];

$routes = new RouteCollection();

$routes->add('mapbender_start', new Route('/', array(
    '_controller' => 'MapbenderCoreBundle:Welcome:list',
)));

$routes->addCollection(
    $loader->import("@FOSJsRoutingBundle/Resources/config/routing/routing.xml"
));

foreach ($defaultAnnotationRoutes as $bundleName) {


    $routingConfigPath = "@" . $bundleName . $path;

    $routes->addCollection(
    // loads routes from the given routing file stored in some bundle
        $loader->import($routingConfigPath, "annotation"));

}

$routes->addCollection(
    $loader->import('mapbender.routing_loader:load', "service"));

return $routes;


<?php

namespace Mapbender\OLCesium\Element;

use Mapbender\CoreBundle\Element\Map;

/**
 * Class Openlayers4 Cesium map element
 *
 */
class OlCesiumMap extends Map
{
    /**
     * @inheritdoc
     */
    public static function getClassTitle()
    {
        return "OpenLayers4 Cesium Map";
    }

    /**
     * @inheritdoc
     */
    public static function getClassDescription()
    {
        return "OpenLayers4 Cesium Map";
    }
    /**
     * @inheritdoc
     */
    public static function listAssets()
    {
        return array(
            'js'  => array(
                '/../vendor/mapbender/mapquery/lib/openlayers/OpenLayers.js',
                '/../vendor/mapbender/mapquery/lib/jquery/jquery.tmpl.js',
                '/../vendor/mapbender/mapquery/src/jquery.mapquery.core.js',
                'proj4js/proj4js-compressed.js',
                'mapbender.element.map.js'),
            'css' => array('@MapbenderCoreBundle/Resources/public/sass/element/map.scss'));
    }
}
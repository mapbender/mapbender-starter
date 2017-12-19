<?php

namespace Mapbender\OLCesiumBundle\Element;

use Doctrine\DBAL\Connection;
use Mapbender\CoreBundle\Element\Map;
use Mapbender\ElementBundle\Component\HttpApiTrait;

/**
 * Class OpenLayers 4 and Cesium map element
 *
 */
class OlCesiumMap extends Map
{
    use HttpApiTrait;

    /** @var string[] Element tag translation subjects */
    protected static $title = 'OpenLayers4 Cesium Map';

    /** @var string Element description translation subject */
    protected static $description  = "OpenLayers4 Cesium Map";

    /**
     * @inheritdoc
     */
    public function getWidgetName()
    {
        return 'mapbender.mbOlCesiumMap';
    }

    /**
     * @inheritdoc
     */
    public function render()
    {
        return '<div class="mb-element mb-element-ol-cesium-map" id="' . $this->getId() . '"></div>';
    }

    /**
     * @inheritdoc
     */
    public static function getType()
    {
        return Type\OlCesiumMapAdminType::class;
    }

    /**
     * @inheritdoc
     */
    public static function getFormTemplate()
    {
        return 'MapbenderOLCesiumBundle:ElementAdmin:olCesiumMap.html.twig';
    }


    /**
     * @inheritdoc
     */
    public static function listAssets()
    {
        return array(
            'js'  => array(
                '@MapbenderElementBundle/Resources/public/mapbender.base.element.js',
                '/components/proj4js/dist/proj4.js',
                'olcesium-debug.js',
                '/components/ol-cesium/Cesium/Cesium.js',
                'map.element.js'
            ),
            'css' => array(
                'map.element.scss'
            )
        );
    }

    /**
     * Returns proj4js srs definitions from a GET parameter srs
     *
     * @param $request
     * @return array srs definitions
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     */
    protected function listSrsAction($request)
    {
        /** @var Connection $db */
        $srsNames = $request['list'];
        $db       = $this->container->get('doctrine')->getConnection();
        return $db->fetchAll('SELECT name, definition FROM mb_core_srs WHERE name IN (' . implode(',', array_map(function ($srsName) use ($db) {
                return $db->quote($srsName);
            }, $srsNames)) . ')');
    }

}
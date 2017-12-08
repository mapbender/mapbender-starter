<?php

namespace Mapbender\OLCesiumBundle\Element;

use Doctrine\DBAL\Connection;
use Mapbender\CoreBundle\Element\Map;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Zumba\Util\JsonSerializer;

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
        return 'OpenLayers4 Cesium Map';
    }

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
    public static function getClassDescription()
    {
        return 'OpenLayers4 Cesium Map';
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

    /**
     * Decode request array variables
     *
     * @param array $request
     * @return mixed
     */
    protected function decodeRequest(array $request)
    {
        foreach ($request as $key => $value) {
            if (is_array($value)) {
                $request[ $key ] = $this->decodeRequest($value);
            } elseif (strpos($key, '[')) {
                preg_match('/(.+?)\[(.+?)\]/', $key, $matches);
                list($match, $name, $subKey) = $matches;

                if (!isset($request[ $name ])) {
                    $request[ $name ] = array();
                }

                $request[ $name ][ $subKey ] = $value;
                unset($request[ $key ]);
            }
        }
        return $request;
    }

    /**
     * @return array|mixed
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     */
    protected function getRequestData()
    {
        $content = $this->container->get('request')->getContent();
        $request = array_merge($_POST, $_GET);

        if (!empty($content)) {
            $request = array_merge($request, json_decode($content, true));
        }

        return $this->decodeRequest($request);
    }

    /**
     * Handles requests (API)
     *
     * Get request "action" variable and run defined action method.
     *
     * Example: if $action="feature/get", then convert name
     *          and run $this->getFeatureAction($request);
     *
     * @inheritdoc
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function httpAction($action)
    {
        $request     = $this->getRequestData();
        $names       = array_reverse(explode('/', $action));
        $namesLength = count($names);
        for ($i = 1; $i < $namesLength; $i++) {
            $names[ $i ][0] = strtoupper($names[ $i ][0]);
        }
        $action     = implode($names);
        $methodName = preg_replace('/[^a-z]+/si', null, $action) . 'Action';
        $result     = $this->{$methodName}($request);

        if (is_array($result)) {
            $serializer = new JsonSerializer();
            $responseBody = $serializer->serialize($result);
            $result     = new Response($responseBody, 200, array('Content-Type' => 'application/json'));
        }

        return $result;
    }
}
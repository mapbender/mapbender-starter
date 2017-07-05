<?php
namespace Mapbender\OLCesium;

use Mapbender\CoreBundle\Component\MapbenderBundle;

/**
 * Mapbender OL Cesium Bundle
 */
class  MapbenderOLCesiumBundle extends MapbenderBundle
{
    /**
     * @inheritdoc
     */
    public function getElements()
    {
        return array(
            'Mapbender\OLCesium\Element\OlCesiumMap'
        );
    }
}


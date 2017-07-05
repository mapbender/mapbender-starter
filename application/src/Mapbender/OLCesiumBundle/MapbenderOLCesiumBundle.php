<?php
namespace Mapbender\OLCesiumBundle;

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
            'Mapbender\OLCesiumBundle\Element\OlCesiumMap'
        );
    }
}


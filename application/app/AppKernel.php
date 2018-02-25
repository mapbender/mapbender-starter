<?php

use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Mapbender\BaseKernel
{
    public function registerBundles()
    {
        $bundles = array(
            // Standard Symfony2 bundles
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),

            // Extra bundles required by Mapbender3/OWSProxy3
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),

            // FoM bundles
            new FOM\CoreBundle\FOMCoreBundle(),
            new FOM\ManagerBundle\FOMManagerBundle(),
            new FOM\UserBundle\FOMUserBundle(),

            // Optional Mapbender bundles
            new Mapbender\WmcBundle\MapbenderWmcBundle(),
            new Mapbender\WmsBundle\MapbenderWmsBundle(),
            new Mapbender\ManagerBundle\MapbenderManagerBundle(),
            new Mapbender\PrintBundle\MapbenderPrintBundle(),
            new Mapbender\DigitizerBundle\MapbenderDigitizerBundle(),
            new Mapbender\MobileBundle\MapbenderMobileBundle(),

            // OWSProxy3 bundles
            new OwsProxy3\CoreBundle\OwsProxy3CoreBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
        );

        // prepend bundles required by Mapbender (including MapbenderCoreBundle)
        $bundles = array_merge(parent::registerBundles(), $bundles);

        // If you get "Uncaught LogicException: Trying to register two bundles with the same name"
        // uncomment the next line for a quick fix
        // $bundles = BaseKernel::filterUniqueBundles($bundles)

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}

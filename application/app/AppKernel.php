<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Mapbender3 kernel
 */
class AppKernel extends Mapbender\BaseKernel
{
    /**
     * Returns an array of bundles to register.
     *
     * @return BundleInterface[] An array of bundle instances.
     */
    public function registerBundles()
    {
        $bundles = array(
            // Standard Symfony2 bundles
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),

            // FoM bundles
            new FOM\CoreBundle\FOMCoreBundle(),
            new FOM\ManagerBundle\FOMManagerBundle(),
            new FOM\UserBundle\FOMUserBundle(),

            // Optional Mapbender bundles
            new Mapbender\WmcBundle\MapbenderWmcBundle(),
            new Mapbender\WmsBundle\MapbenderWmsBundle(),
            new Mapbender\ManagerBundle\MapbenderManagerBundle(),
            new Mapbender\PrintBundle\MapbenderPrintBundle(),
            new Mapbender\MobileBundle\MapbenderMobileBundle(),

            // OWSProxy3 bundles
            new OwsProxy3\CoreBundle\OwsProxy3CoreBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
        );

        $this->addNameSpaceBundles($bundles, 'Mapbender\\');

        // prepend bundles required by Mapbender (including MapbenderCoreBundle)
        $bundles = array_merge(parent::registerBundles(), $bundles);

        // If you get "Uncaught LogicException: Trying to register two bundles with the same name"
        // uncomment the next line for a quick fix
        // $bundles = BaseKernel::filterUniqueBundles($bundles)

        return $bundles;
    }

    /**
     * Loads the container configuration.
     *
     * @param LoaderInterface $loader A LoaderInterface instance
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}

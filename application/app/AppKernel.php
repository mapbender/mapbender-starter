<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Mapbender3 kernel
 */
class AppKernel extends Kernel
{
    /**
     * Search and initialize name space bundles.
     * Search approach uses indirectly the composer auto generated file to get bundle names.
     *
     * @param BundleInterface[] $bundles   Bundle array link
     * @param string            $nameSpace Name space prefix as string
     * @return BundleInterface[] Bundle array
     */
    function addNameSpaceBundles(array &$bundles, $nameSpace)
    {
        $namespaces = include(dirname(__FILE__) . "/../vendor/composer/autoload_namespaces.php");
        foreach ($namespaces as $name => $path) {
            if (strpos($name, $nameSpace) === 0) {
                $bundleClassName = $name . '\\' . str_replace('\\', "", $name);
                $bundles[] = new $bundleClassName();
            }

        }

        $namespaces = include(dirname(__FILE__) . "/../vendor/composer/autoload_psr4.php");
        foreach ($namespaces as $name => $path) {
            if (strpos($name, $nameSpace) === 0
                && strpos($name, "Bundle")
            ) {
                $bundleClassName = $name . str_replace('\\', "", $name);
                $bundles[] = new $bundleClassName();
            }
        }
        return $bundles;
    }

    /**
     * Returns an array of bundles to register.
     *
     * @return BundleInterface[] An array of bundle instances.
     */
    public function registerBundles()
    {
        $bundles = array(
            // Standard Symfony2 bundles
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            // Extra bundles required by Mapbender3/OWSProxy3
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),

            // FoM bundles
            new FOM\CoreBundle\FOMCoreBundle(),
            new FOM\ManagerBundle\FOMManagerBundle(),
            new FOM\UserBundle\FOMUserBundle(),

            // Mapbender3 bundles
            new Mapbender\CoreBundle\MapbenderCoreBundle(),
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

        // dev and ALL test environments get some extra sugar...
        $isDevKernel = false;
        if('dev' == $this->getEnvironment() || strpos($this->getEnvironment(), 'test') == 0) {
            $isDevKernel = true;
        }

        if ($isDevKernel) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

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

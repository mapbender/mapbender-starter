<?php


/**
 * Partial reimplementation of (abandoned, Composer 2 incompatible) robloach/component-installer package
 * @see https://packagist.org/packages/robloach/component-installer
 *
 * Reimplements ONLY the mirroring portion (copy specified subset of files from vendor into web/components)
 * DOES NOT reimplement the CSS / JS recompilation processes.
 *
 * No known replacement package suggestion for robloach/component-installer currently supports install location
 * mirroring.
 */
class ComponentMirror
{
    public static function postAutoloadDump(\Composer\Script\Event $event)
    {
        $packages = static::getCopyPackages($event->getComposer());
        foreach ($packages as $package) {
            static::copyPackage($event->getComposer(), $event->getIO(), $package);
        }
    }

    protected static function getCopyPackages(\Composer\Composer $composer)
    {
        /** @var \Composer\Installer\InstallationManager $im */
        $im = $composer->getInstallationManager();
        /** @var \Composer\Package\RootPackageInterface|null $rootPackage */
        $rootPackage = $composer->getPackage();
        /** @var \Composer\Package\Locker|null $locker */
        $locker = $composer->getLocker();
        /** @var \Composer\Config $config */
        $config = $composer->getConfig();
        if (!$rootPackage || !$locker || !$locker->isLocked() || !$config->get('component-dir')) {
            // Uh-oh!
            return array();
        }

        $vendorDir = \realpath($config->get('vendor-dir'));
        $lockData = $locker->getLockData();
        $prodPackages = ($lockData && !empty($lockData['packages'])) ? $lockData['packages'] : array();
        $devPackages = ($lockData && !empty($lockData['packages-dev'])) ? $lockData['packages-dev'] : array();
        /** @var \Composer\Package\PackageInterface[] $packages */
        $packages = array();
        $loader = new \Composer\Package\Loader\ArrayLoader();
        foreach (array_merge($prodPackages, $devPackages) as $pkgInfo) {
            if (!empty($pkgInfo['type']) && !empty($pkgInfo['name']) && $pkgInfo['type'] === 'component') {
                /** @var \Composer\Package\CompletePackageInterface $package */
                $package = $loader->load($pkgInfo);
                $extra = $package->getExtra();
                if (false !== strpos($im->getInstallPath($package), $vendorDir) && !empty($extra['component'])) {
                    $packages[] = $package;
                }
            }
        }
        return $packages;
    }

    protected static function copyPackage(\Composer\Composer $composer, \Composer\IO\IOInterface $io, \Composer\Package\PackageInterface $package)
    {
        /** @var \Composer\Installer\InstallationManager $im */
        $im = $composer->getInstallationManager();
        /** @var \Composer\Config $config */
        $config = $composer->getConfig();
        $fs = new \Symfony\Component\Filesystem\Filesystem();
        $fs->mkdir($config->get('component-dir'));
        $componentDir = \realpath($config->get('component-dir'));
        $extra = $package->getExtra();
        if (!empty($extra['component']['name'])) {
            $targetDir = $componentDir . '/' . $extra['component']['name'];
        } else {
            $nameParts = explode('/', $package->getName());
            if (count($nameParts) !== 2) {
                throw new \RuntimeException("Don't know where to copy {$package->getName()}");
            }
            $targetDir = $componentDir . '/' . $nameParts[1];
        }
        $io->write("Copying package {$package->getName()} files into {$targetDir}");
        $patterns = array();
        foreach (array('scripts', 'styles', 'files') as $patternKey) {
            if (!empty($extra['component'][$patternKey])) {
                $patterns = array_merge($patterns, $extra['component'][$patternKey]);
            }
        }
        $patterns = array_unique($patterns);
        $sourceDir = \realpath($im->getInstallPath($package));
        $options = array(
            // Avoid copying symlinks as symlinks (any platform)
            'copy_on_windows' => true,
        );
        $fs->mirror($sourceDir, $targetDir, static::buildMirrorIterator($sourceDir, $patterns), $options);
    }

    protected static function buildMirrorIterator($sourcePath, $patterns)
    {
        $matchedFiles = array();
        foreach ($patterns as $pattern) {
            // Expand glob
            $matchedByPattern = \iterator_to_array(new \GlobIterator($sourcePath . '/' . $pattern, FilesystemIterator::SKIP_DOTS));
            $matchedFiles += $matchedByPattern;
            foreach ($matchedByPattern as $matchInfo) {
                /** @var \SplFileInfo $matchInfo */
                if ($matchInfo->isDir()) {
                    $subDirIterator = new \RecursiveDirectoryIterator($matchInfo->getRealPath(), FilesystemIterator::SKIP_DOTS);
                    $matchedFiles += \iterator_to_array(new \RecursiveIteratorIterator($subDirIterator));
                }
            }
        }
        // Rewrap as (rewindable) iterator
        return new \ArrayIterator(new \ArrayObject($matchedFiles));
    }
}

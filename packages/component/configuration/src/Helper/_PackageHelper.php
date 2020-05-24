<?php
declare(strict_types=1);

namespace Silvermoon\Configuration\Helper;

use Silvermoon\Configuration\StructModel\_Package;
use Silvermoon\Exception\System\FileNotFoundException;

/**
 * Class _PackagesHelper
 */
class _PackageHelper
{
    /**
     * @param string $pathToVendor
     * @return _Package[]
     * @throws FileNotFoundException
     */
    public static function packagesInPath(string $pathToVendor): array
    {
        $directories = _DirectoryHelper::directoriesInPathAbsolutePath($pathToVendor, 2, 1);
        $packages = [];
        foreach ($directories as $directory) {
            $package = self::readPackage($directory);
            if ($package !== null) {
                $packages[] = $package;
            }
        }
        return $packages;
    }

    /**
     * @param string $path
     * @return _Package|null
     * @throws FileNotFoundException
     */
    protected static function readPackage(string $path): ?_Package
    {
        $composerFile = $path . '/composer.json';
        if (\file_exists($composerFile) === false) {
            return null;
        }
        $composerContent = \file_get_contents($composerFile);
        if ($composerContent === false) {
            throw new FileNotFoundException();
        }
        $composerData = \json_decode($composerContent, true);
        if ($composerData === null) {
            return null;
        }
        if (\array_key_exists('name', $composerData) === false) {
            return null;
        }
        if (\array_key_exists('type', $composerData) === false) {
            return null;
        }
        $package = new _Package();
        $package->name = $composerData['name'];
        $package->type = $composerData['type'];
        $package->path = $path;

        return $package;
    }
}

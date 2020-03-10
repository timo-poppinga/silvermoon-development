<?php
declare(strict_types=1);

namespace Silvermoon\Configuration\Service;

use Silvermoon\Configuration\Helpers\DirectoryHelper;
use Silvermoon\Configuration\Model\Package;

/**
 * Class PackagesService
 */
class PackagesService
{
    /**
     * @param string $pathToVendorDir
     * @return Package[]
     */
    public static function readPackages(string $pathToVendorDir = './vendor'): array
    {
        if (\is_dir($pathToVendorDir) === false) {
            return [];
        }
        $directories = DirectoryHelper::directoriesInPath($pathToVendorDir, 1, 1, true);
        $packages = [];
        foreach ($directories as $directory) {
            $composerFilePath = $directory . '/composer.json';
            if (\file_exists($composerFilePath) === false) {
                continue;
            }
            $composerData = \json_decode(\file_get_contents($composerFilePath), true);
            if ($composerData === null) {
                continue;
            }
            $package = new Package();
            $package->name = $composerData['name'];
            $package->type = $composerData['type'];
            $packages[] = $package;
        }
        return $packages;
    }
}

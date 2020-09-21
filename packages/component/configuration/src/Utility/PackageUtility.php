<?php
declare(strict_types=1);

namespace Silvermoon\Configuration\Service;

use Silvermoon\Configuration\Exception\ComposerParseException;
use Silvermoon\Configuration\Struct\Package;
use Silvermoon\Toolbox\Utility\ComposerUtility;
use Silvermoon\Toolbox\Utility\FilesystemUtility;

/**
 * Class PackageUtility
 * @package Silvermoon\Configuration\Service
 */
class PackageUtility
{
    /**
     * @param string[] $pathToPackages
     * @return Package[]
     */
    public function readPackages(array $pathToPackages): array
    {
        $packages = [];
        foreach ($pathToPackages as $pathToPackage) {
            $packages = \array_merge($packages, $this->readPackagesInPath($pathToPackage));
        }
        return $packages;
    }


    /**
     * @param string $pathToPackage
     * @return Package[]
     */
    protected function readPackagesInPath(string $pathToPackage): array
    {
        $packages = [];
        $pathOfPackages = FilesystemUtility::readDirectoriesInPath($pathToPackage, 1, 1, true);
        foreach ($pathOfPackages as $pathOfPackage) {
            $packages[] = $this->readPackage($pathOfPackage);
        }
        return $packages;
    }

    /**
     * @param string $pathOfPackage
     * @return Package
     */
    protected function readPackage(string $pathOfPackage): Package
    {
        $composerArray = ComposerUtility::readComposerJson($pathOfPackage . '/composer.json');
        $package = new Package();
        $package->path = $pathOfPackage;
        if(\array_key_exists('name', $composerArray) === false) {
            throw new ComposerParseException(1600624169);
        }
        $package->name = $composerArray['name'];
        if(\array_key_exists('type', $composerArray)) {
            $package->type = $composerArray['type'];
        }
        return $package;
    }
}

<?php
declare(strict_types=1);

namespace Silvermoon\Configuration\Service;

use Silvermoon\Configuration\Struct\Package;
use Silvermoon\Toolbox\Utility\FilesystemUtility;

/**
 * Class ConfigurationService
 */
class ConfigurationService
{
    /**
     * @return mixed[]
     */
    public function readConfiguration(): array
    {
        $pathUtility = new PathUtility();
        $path = $pathUtility->readPath();

        $packageUtility = new PackageUtility();
        $packages = $packageUtility->readPackages($path->vendorPath);
        return $this->readPack($packages);
    }

    /**
     * @param Package[] $packages
     * @return mixed[]
     */
    protected function readPack(array $packages): array
    {
        $configurationUtility = new ConfigurationUtility();
        $configuration = [];
        foreach ($packages as $package) {
            $configurationFiles = FilesystemUtility::readFilesInPath($package->path . 'configuration', 512, 0, true, ['yaml']);
            foreach ($configurationFiles as $configurationFile) {
                $configurationUtility->loadYamlFileAndMergeWitchConfiguration($configuration, $configurationFile);
            }
        }
        return $configuration;
    }
}

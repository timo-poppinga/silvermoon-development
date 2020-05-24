<?php
declare(strict_types=1);

namespace Silvermoon\Configuration\Service;

use Silvermoon\Configuration\Helper\_ConfigMergeHelper;
use Silvermoon\Configuration\Helper\_FileHelper;
use Silvermoon\Configuration\Helper\_PackageHelper;
use Silvermoon\Exception\System\FileNotFoundException;
use Silvermoon\Exception\System\PathNotFoundException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class PackagesService
 */
class ConfigurationLoader
{
    /**
     * @param string $rootPath
     * @param string $vendorPath
     * @return array<mixed>
     * @throws FileNotFoundException
     * @throws PathNotFoundException
     */
    public function load(string $rootPath = '.', string $vendorPath = 'vendor'): array
    {
        $configuration = [];
        $realRootPath = \realpath($rootPath);
        $pathToYamlFile = $realRootPath . '/.config.yaml';
        if (\is_file($pathToYamlFile)) {
            $configuration = $this->loadYamlFileAndMergeWitchConfiguration($configuration, $pathToYamlFile);
        }
        $path = \realpath($rootPath . '/' . $vendorPath);
        if ($path === false) {
            throw new PathNotFoundException();
        }
        $packages = _PackageHelper::packagesInPath($path);
        foreach ($packages as $package) {
            $configPath = $package->path . '/configuration';
            if (\is_dir($configPath) === false) {
                continue;
            }
            $configFiles = _FileHelper::filesInPathAbsolutePath($configPath, 'yaml');
            foreach ($configFiles as $configFile) {
                $configuration = $this->loadYamlFileAndMergeWitchConfiguration($configuration, $configFile);
            }
        }
        return $configuration;
    }

    /**
     * @param array<mixed> $configuration
     * @param string $pathToYamlFile
     * @return array<mixed>
     * @throws FileNotFoundException
     */
    public function loadYamlFileAndMergeWitchConfiguration(array $configuration, string $pathToYamlFile): array
    {
        if (\is_file($pathToYamlFile) === false) {
            return $configuration;
        }
        $fileContent = \file_get_contents($pathToYamlFile);
        if ($fileContent === false) {
            throw new FileNotFoundException();
        }
        $newConfiguration = Yaml::parse($fileContent);
        return _ConfigMergeHelper::mergeConfigRecursive($configuration, $newConfiguration);
    }
}

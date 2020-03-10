<?php
declare(strict_types=1);

namespace Silvermoon\Configuration\Service;

use Silvermoon\Configuration\Helpers\DirectoryHelper;
use Silvermoon\Configuration\Model\Package;
use Symfony\Component\Yaml\Yaml;

/**
 * Class PackagesService
 */
class ConfigurationLoader
{
    public function load(string $rootPath = '')
    {
        $realRootPath = \realpath($rootPath);
        $configuration = $this->loadYamlFileAndMergeWitchConfiguration([], $rootPath . '/.config.yaml');
        $packagePath = 'vendor';
        if(\array_key_exists('packagePath', $configuration)) {
            $packagePath = $configuration['packagePath'];
        }
        $realPackagePath = \realpath($realRootPath . '/' . $packagePath);
        $pathToPackages = DirectoryHelper::directoriesInPath($realPackagePath, 1 , 1, true);
        foreach ($pathToPackages as $pathToPackage) {
            $pathToConfiguration = $pathToPackage . '/configuration';
            if(\is_dir($pathToConfiguration) === false) {
                continue;
            }
        }
    }

    /**
     * @param array $configuration
     * @param $pathToYamlFile
     * @return array
     */
    public function loadYamlFileAndMergeWitchConfiguration(array $configuration, $pathToYamlFile): array
    {
        if(\is_file($pathToYamlFile)) {
            return $configuration;
        }
        $newConfiguration = Yaml::parse(\file_get_contents($pathToYamlFile));
        return array_merge($configuration, $newConfiguration);
    }
}
<?php
declare(strict_types=1);

namespace Silvermoon\Configuration\Service;

use Silvermoon\Configuration\Exception\ConfigurationFileNotFoundException;
use Silvermoon\Configuration\Helper\_ConfigMergeHelper;
use Silvermoon\Configuration\Helper\_FileHelper;
use Silvermoon\Configuration\Helper\_PackageHelper;
use Silvermoon\Configuration\Internal\Utility\ConfigurationMergeUtility;
use Silvermoon\Exception\System\FileNotFoundException;
use Silvermoon\Exception\System\PathNotFoundException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class PackagesService
 * @SuppressWarnings(PHPMD)
 */
class ConfigurationUtility
{
    /**
     * @param mixed[] $configuration
     * @param string $pathToYamlFile
     * @return mixed[]
     */
    public function loadYamlFileAndMergeWitchConfiguration(array $configuration, string $pathToYamlFile): array
    {
        if (\is_file($pathToYamlFile) === false) {
            throw new ConfigurationFileNotFoundException(1600625934);
        }
        $fileContent = \file_get_contents($pathToYamlFile);
        if ($fileContent === false) {
            throw new ConfigurationFileNotFoundException(1600625934);
        }
        $newConfiguration = Yaml::parse($fileContent);
        return ConfigurationMergeUtility::mergeConfigRecursive($configuration, $newConfiguration);
    }
}

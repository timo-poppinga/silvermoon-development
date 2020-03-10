<?php
declare(strict_types=1);

namespace Silvermoon\Configuration;

/**
 * Class Configuration.php
 */
class Configuration
{
    protected array $currentConfig = [];

    /**
     * @param string $path
     * @param string $default
     * @return string
     */
    public function readConfigurationAsString(string $path, ?string $default = null): ?string
    {
        $configuration = $this->readConfiguration($path);
        if (\is_string($configuration) === true) {
            return $configuration;
        }
        return $default;
    }

    /**
     * @param string $path
     * @return bool|mixed
     */
    private function readConfiguration(string $path)
    {
        $pathArray = \explode('/', $path);
        $currentConfigPosition = $this->currentConfig;
        foreach ($pathArray as $pathSegment) {
            if (\is_array($currentConfigPosition) === false|| array_key_exists($pathSegment, $currentConfigPosition) === false) {
                return null;
            }
            $currentConfigPosition = $currentConfigPosition[$pathSegment];
        }
        return $currentConfigPosition;
    }


    /**
     * @param string $path
     * @param $value
     */
    public function setConfiguration(string $path, $value)
    {
        $pathArray = \explode('/', $path);
        $currentConfigPosition = &$this->currentConfig;
        for ($i = 0; $i < \count($pathArray); $i++) {
            $pathSegment = $pathArray[$i];
            if ($i < \count($pathArray) - 1) {
                if (\array_key_exists($pathSegment, $currentConfigPosition) === false) {
                    $currentConfigPosition[$pathSegment] = [];
                }
                $currentConfigPosition = &$currentConfigPosition[$pathSegment];
                continue;
            }
            $currentConfigPosition[$pathSegment] = $value;
        }
    }
}

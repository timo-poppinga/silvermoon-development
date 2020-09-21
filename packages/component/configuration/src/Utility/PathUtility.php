<?php
declare(strict_types=1);

namespace Silvermoon\Configuration\Service;

use Silvermoon\Configuration\Struct\Path;
use Silvermoon\Toolbox\Utility\ComposerUtility;

/**
 * Class PathUtility
 * @package Silvermoon\Configuration\Service
 */
class PathUtility
{
    /**
     * @return Path
     */
    public function readPath(): Path
    {
        $path = new Path();
        $path->rootPath = \getcwd();
        $composerArray = ComposerUtility::readComposerJson($path->rootPath . '/composer.json');
        $this->parseComposer($composerArray, $path);
        return $path;
    }

    /**
     * @param array $composerArray
     * @param Path $path
     */
    protected function parseComposer(array $composerArray, Path $path): void
    {
        if (\array_key_exists('config', $composerArray) === false) {
            return;
        }
        $config = $composerArray['config'];
        $path->vendorPath = $path->rootPath . '/' . $this->buildPath($config, 'vendor-dir', 'vendor');
        $path->binPath = $path->rootPath . '/' . $this->buildPath($config, 'bin-dir', 'vendor/bin');
    }

    /**
     * @param array $config
     * @param string $key
     * @param string $default
     * @return string
     */
    protected function buildPath(array $config, string $key, string $default): string
    {
        if (\array_key_exists($key, $config) === false) {
            return $default;
        }
        return $config[$key];
    }
}

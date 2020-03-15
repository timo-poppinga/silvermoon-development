<?php
declare(strict_types=1);

namespace Silvermoon\Configuration\Helper;

/**
 * Class _DirectoryHelper
 */
class _DirectoryHelper
{
    /**
     * @param string $path
     * @param int $depth
     * @param int $startAtDepth
     * @param bool $absolutePath
     * @return string[]
     */
    public static function directoriesInPath(string $path, int $depth = 0, int $startAtDepth = 0, bool $absolutePath = false): array
    {
        $realpathPath = \realpath($path);
        $directories = self::_directoriesInPath($realpathPath, $depth, $startAtDepth);
        if ($absolutePath === true) {
            return $directories;
        }
        $relativeDirectories = [];
        foreach ($directories as $directory) {
            $relativeDirectories[] = substr($directory, strlen($realpathPath) + 1);
        }
        return $relativeDirectories;
    }

    /**
     * @param string $path
     * @param int $depth
     * @param int $startAtDepth
     * @param bool $absolutePath
     * @param int $currentDeep
     * @return string[]
     */
    protected static function _directoriesInPath(string $path, int $depth = 0, int $startAtDepth = 0, int $currentDepth = 0): array
    {
        $directories = [];
        if (\is_dir($path)) {
            $directoryNames = scandir($path);
            foreach ($directoryNames as $directoryName) {
                if (\is_dir($path . '/' . $directoryName) && $directoryName !== '..' && $directoryName !== '.') {
                    if ($startAtDepth <= $currentDepth) {
                        $directories[] = \realpath($path . '/' . $directoryName);
                    }
                    if ($currentDepth < $depth) {
                        $subDirectories = self::_directoriesInPath($path . '/' . $directoryName, $depth, $startAtDepth, $currentDepth + 1);
                        $directories = \array_merge($directories, $subDirectories);
                    }
                }
            }
        }
        return $directories;
    }
}

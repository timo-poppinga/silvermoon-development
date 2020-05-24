<?php
declare(strict_types=1);

namespace Silvermoon\Configuration\Helper;

use Silvermoon\Exception\System\PathNotFoundException;

/**
 * Class _DirectoryHelper
 */
class _DirectoryHelper
{
    /**
     * @param string $path
     * @param int $depth
     * @param int $startAtDepth
     * @return array<string>
     * @throws PathNotFoundException
     */
    public static function directoriesInPath(string $path, int $depth = 0, int $startAtDepth = 0): array
    {
        $realpathPath = \realpath($path);
        if($realpathPath === false) {
            throw new PathNotFoundException();
        }
        $directories = self::directoriesInPathAbsolutePath($realpathPath, $depth, $startAtDepth);
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
     * @return array<string>
     * @throws PathNotFoundException
     */
    public static function directoriesInPathAbsolutePath(string $path, int $depth = 0, int $startAtDepth = 0): array
    {
        $realpathPath = \realpath($path);
        if($realpathPath === false) {
            throw new PathNotFoundException();
        }
        return self::_directoriesInPath($realpathPath, $depth, $startAtDepth);
    }

    /**
     * @param string $path
     * @param int $depth
     * @param int $startAtDepth
     * @param int $currentDepth
     * @return string[]
     * @throws PathNotFoundException
     */
    protected static function _directoriesInPath(string $path, int $depth = 0, int $startAtDepth = 0, int $currentDepth = 0): array
    {
        $directories = [];
        if (\is_dir($path)) {
            $directoryNames = scandir($path);
            if($directoryNames === false) {
                throw new PathNotFoundException();
            }
            foreach ($directoryNames as $directoryName) {
                if (\is_dir($path . '/' . $directoryName) && $directoryName !== '..' && $directoryName !== '.') {
                    if ($startAtDepth <= $currentDepth) {
                        $realPath = \realpath($path . '/' . $directoryName);
                        if($realPath === false) {
                            throw new PathNotFoundException();
                        }
                        $directories[] = $realPath;
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

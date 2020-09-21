<?php

declare(strict_types=1);

namespace Silvermoon\Toolbox\Utility;

use Silvermoon\Toolbox\Exception\DirectoryNotFoundException;

class FilesystemUtility
{

    /**
     * @param string $path
     * @param int $depth
     * @param int $startAtDepth
     * @param bool $absolutePath
     * @param string[] $extensionList
     * @return string[]
     */
    public static function readFilesInPath(string $path, int $depth = 512, int $startAtDepth = 0, bool $absolutePath = false, array $extensionList = []): array
    {
        $realPath = \realpath($path);
        $paths = self::_readInPath($realPath, $depth, $startAtDepth, $extensionList, false);
        return self::buildReturn($realPath, $paths, $absolutePath);
    }

    /**
     * @param string $path
     * @param int $depth
     * @param int $startAtDepth
     * @param bool $absolutePath
     * @return string[]
     */
    public static function readDirectoriesInPath(string $path, int $depth = 512, int $startAtDepth = 0, bool $absolutePath = false): array
    {
        $realPath = \realpath($path);
        $paths = self::_readInPath($realPath, $depth, $startAtDepth, [], true);
        return self::buildReturn($realPath, $paths, $absolutePath);
    }

    /**
     * @param string $basePath
     * @param string[] $paths
     * @param bool $absolutePath
     * @return string[]
     */
    protected static function buildReturn(string $basePath, array $paths, bool $absolutePath): array
    {
        if($absolutePath === false) {
            return $paths;
        }
        $newPaths = [];
        foreach ($paths as $path) {
            $newPaths[] = $basePath . '/' . $path;
        }
        return $newPaths;
    }

    /**
     * @param string $path
     * @param int $depth
     * @param int $startAtDepth
     * @param string[] $extensionList
     * @param bool $isLoadDirectories
     * @param int $currentDepth
     * @param string $currentPath
     * @return string[]
     */
    protected static function _readInPath(string $path, int $depth, int $startAtDepth, array $extensionList, bool $isLoadDirectories, int $currentDepth = 0, string $currentPath = ''): array
    {
        if (\is_dir($path) === false) {
            throw new DirectoryNotFoundException(1600607880);
        }
        $paths = [];
        $pathParts = \scandir($path);
        if ($pathParts === false) {
            throw new DirectoryNotFoundException(1600607911);
        }
        foreach ($pathParts as $pathPart) {
            if ($pathPart === '..' || $pathPart === '.') {
                continue;
            }

            $isStart = $currentDepth >= $startAtDepth;
            $isReadDeeper = $currentDepth < ($startAtDepth + $depth);
            $isDirectory = \is_dir($path . '/' . $pathPart);

            if ($isLoadDirectories) {
                $isReadDeeper = $currentDepth < ($startAtDepth + $depth - 1);
            }


            $pathToAdd = $currentPath . $pathPart;

            if ($isStart) {
                if ($isLoadDirectories === true && $isDirectory === true) {
                    $paths[] = $pathToAdd;
                }
                if ($isLoadDirectories === false && $isDirectory === false) {
                    if (\count($extensionList) > 0 && \in_array(\pathinfo($pathPart, PATHINFO_EXTENSION), $extensionList) === false) {
                        continue;
                    }
                    $paths[] = $pathToAdd;
                }
            }


            if ($isDirectory && $isReadDeeper) {
                $newPath = $path . '/' . $pathPart;
                $newCurrentDepth = $currentDepth + 1;
                $newCurrentPath = $currentPath . $pathPart . '/';
                $subDirectories = self::_readInPath($newPath, $depth, $startAtDepth, $extensionList, $isLoadDirectories, $newCurrentDepth, $newCurrentPath);
                $paths = \array_merge($paths, $subDirectories);
            }
        }
        return $paths;
    }
}

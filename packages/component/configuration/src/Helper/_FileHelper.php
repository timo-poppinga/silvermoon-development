<?php
declare(strict_types=1);

namespace Silvermoon\Configuration\Helper;

use Silvermoon\Exception\System\PathNotFoundException;

/**
 * Class FileHelper
 */
class _FileHelper
{
    /**
     * @param string $path
     * @param string|null $fileExtensionFilter
     * @return array<string>
     * @throws PathNotFoundException
     */
    public static function filesInPathAbsolutePath(string $path, ?string $fileExtensionFilter = null): array
    {
        $realpathPath = \realpath($path);
        if ($realpathPath === false) {
            throw new PathNotFoundException();
        }
        $files = self::filesInPath($realpathPath, $fileExtensionFilter);
        $filesWitchPath = [];
        foreach ($files as $file) {
            $filesWitchPath[] = $realpathPath . '/' . $file;
        }
        return $filesWitchPath;
    }

    /**
     * @param string $path
     * @param string|null $fileExtensionFilter
     * @return array<string>
     * @throws PathNotFoundException
     */
    public static function filesInPath(string $path, ?string $fileExtensionFilter = null): array
    {
        $realpathPath = \realpath($path);
        if ($realpathPath === false) {
            return [];
        }
        if (\is_dir($realpathPath) === false) {
            return [];
        }
        $directoryItems = \scandir($realpathPath);
        if ($directoryItems === false) {
            throw new PathNotFoundException();
        }
        $files = [];
        foreach ($directoryItems as $directoryItem) {
            if (\is_file($path . '/' . $directoryItem) === false) {
                continue;
            }
            if ($fileExtensionFilter) {
                $parts = \explode('.', $directoryItem);
                if (count($parts) !== 2 || $parts[1] !== $fileExtensionFilter) {
                    continue;
                }
            }
            $files[] = $directoryItem;
        }
        return $files;
    }
}

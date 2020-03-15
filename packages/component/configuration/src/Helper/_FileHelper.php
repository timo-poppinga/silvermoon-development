<?php
declare(strict_types=1);

namespace Silvermoon\Configuration\Helper;

/**
 * Class FileHelper
 */
class _FileHelper
{
    /**
     * @param string $path
     * @param string|null $fileExtensionFilter
     * @param bool $absolutePath
     * @return array
     */
    public static function filesInPath(string $path, ?string $fileExtensionFilter = null, bool $absolutePath = false): array
    {
        $realpathPath = \realpath($path);
        if($realpathPath === false) {
            return [];
        }
        if (\is_dir($realpathPath) === false) {
            return [];
        }
        $directoryItems = \scandir($realpathPath);
        $files = [];
        foreach ($directoryItems as $directoryItem) {
            if(\is_file($path . '/' . $directoryItem) === false) {
               continue;
            }
            if($fileExtensionFilter) {
                $parts = \explode('.', $directoryItem);
                if(count($parts) !== 2 || $parts[1] !== $fileExtensionFilter) {
                    continue;
                }
            }

            if($absolutePath) {
                $files[] = $path . '/' . $directoryItem;
                continue;
            }
            $files[] = $directoryItem;
        }
        return $files;
    }
}

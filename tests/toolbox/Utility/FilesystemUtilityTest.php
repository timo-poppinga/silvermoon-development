<?php
declare(strict_types=1);

namespace SilvermoonTests\Toolbox\Utility;

use Silvermoon\TestingFramework\BaseUnitTest;
use Silvermoon\Toolbox\Utility\FilesystemUtility;

class FilesystemUtilityTest extends BaseUnitTest
{
    public function testReadFilesInPath(): void
    {
        $depth = 1;
        $startAtDepth = 1;
        $files = FilesystemUtility::readFilesInPath('vendor', $depth, $startAtDepth, false);
        foreach ($files as $file) {
            $parts = \explode('/', $file);
            $partsCount = \count($parts);
            self::assertTrue($partsCount <= ($depth + $startAtDepth + 1));
            self::assertTrue($partsCount > $startAtDepth);
        }
    }

    public function testReadDirectoriesInPathStartEndDepth(): void
    {
        $depth = 1;
        $startAtDepth = 1;
        $directories = FilesystemUtility::readDirectoriesInPath('vendor', $depth, $startAtDepth, false);
        foreach ($directories as $directory) {
            $parts = \explode('/', $directory);
            $partsCount = \count($parts);
            self::assertTrue($partsCount <= ($depth + $startAtDepth));
            self::assertTrue($partsCount > $startAtDepth);
        }
    }
}

<?php
declare(strict_types=1);

namespace SilvermoonTests\Configuration\Helpers;

use Silvermoon\Configuration\Service\PathUtility;
use Silvermoon\Configuration\Struct\Path;
use Silvermoon\TestingFramework\BaseUnitTest;

class PathUtilityTest extends BaseUnitTest
{
    public function testReadPath(): void
    {
        $pathService = new PathUtility();
        $path = $pathService->readPath();
        self::assertInstanceOf(Path::class, $path);

        self::assertSame('/opt/project', $path->rootPath);
        self::assertSame('/opt/project/vendor', $path->vendorPath);
        self::assertSame('/opt/project/bin', $path->binPath);
    }
}

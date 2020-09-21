<?php
declare(strict_types=1);

namespace SilvermoonTests\Configuration\Helpers;

use Silvermoon\Configuration\Service\PackageUtility;
use Silvermoon\TestingFramework\BaseUnitTest;

class PathServiceTest extends BaseUnitTest
{
    public function testReadPath(): void
    {
        $packageService = new PackageUtility();
        $packages = $packageService->readPackages(['vendor']);
        self::assertCount(75, $packages);
    }
}

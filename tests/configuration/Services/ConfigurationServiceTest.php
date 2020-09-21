<?php
declare(strict_types=1);

namespace SilvermoonTests\Configuration\Services;

use Silvermoon\Configuration\Service\PackageUtility;
use Silvermoon\TestingFramework\BaseUnitTest;

class ConfigurationServiceTest extends BaseUnitTest
{
    public function testReadPath(): void
    {
        $packageService = new PackageUtility();
        $packages = $packageService->readPackages(['vendor']);
        self::assertCount(75, $packages);
    }
}

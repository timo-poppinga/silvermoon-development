<?php
declare(strict_types=1);

namespace SilvermoonTests\Configuration\Helpers;

use Silvermoon\Configuration\Services\PackagesService;
use Silvermoon\TestingFramework\BaseUnitTest;

class PackagesServiceTest extends BaseUnitTest
{
    protected string $examplePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->examplePath = \realpath(\getcwd() . '/../Fixtures/FilesAndDirectoriesExample');
    }

    public function testReadPackages()
    {
        $packages = PackagesService::readPackages($this->examplePath);
        $this->assertSame(['Helpers', 'Services'], $packages);
    }
}

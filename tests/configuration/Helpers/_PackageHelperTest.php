<?php
declare(strict_types=1);

namespace SilvermoonTests\Configuration\Helpers;

use Silvermoon\Configuration\Helper\_PackageHelper;
use Silvermoon\TestingFramework\BaseUnitTest;

class _PackageHelperTest extends BaseUnitTest
{
    protected string $examplePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->examplePath = \realpath(\getcwd() . '/../Fixtures/vendor');
    }

    public function testPackagesInPath()
    {
        $packages = _PackageHelper::packagesInPath($this->examplePath);
        $this->assertCount(4, $packages);
    }
}

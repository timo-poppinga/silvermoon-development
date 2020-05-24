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
        $currentWorkingDirectory = \getcwd();
        $realPath = \realpath($currentWorkingDirectory . '/../Fixtures/vendor');
        if($realPath === false) {
            $realPath = \realpath($currentWorkingDirectory . '/tests/configuration/Fixtures/vendor');
        }
        $this->examplePath = $realPath;
    }

    public function testPackagesInPath()
    {
        $packages = _PackageHelper::packagesInPath($this->examplePath);
        $this->assertCount(4, $packages);
    }
}

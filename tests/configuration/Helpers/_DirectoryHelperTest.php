<?php
declare(strict_types=1);

namespace SilvermoonTests\Configuration\Helpers;

use Silvermoon\Configuration\Helper\_DirectoryHelper;
use Silvermoon\TestingFramework\BaseUnitTest;

class _DirectoryHelperTest extends BaseUnitTest
{
    protected string $examplePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->examplePath = \realpath(\getcwd() . '/../Fixtures/FilesAndDirectoriesExample');
    }


    public function testLoadDirectories()
    {
        $directories = _DirectoryHelper::directoriesInPath($this->examplePath);
        $this->assertSame(['Helpers', 'Services'], $directories);
    }

    public function testLoadDirectoriesInDepthTow()
    {
        $directories = _DirectoryHelper::directoriesInPath($this->examplePath, 1);
        $this->assertSame(['Helpers', 'Services', 'Services/MuchNicer', 'Services/Nice'], $directories);
    }

    public function testLoadDirectoriesInDepthTowStartAtTow()
    {
        $directories = _DirectoryHelper::directoriesInPath($this->examplePath, 1, 1);
        $this->assertSame(['Services/MuchNicer', 'Services/Nice'], $directories);
    }

    public function testLoadDirectoriesInDepthTowFull()
    {
        $directories = _DirectoryHelper::directoriesInPathAbsolutePath($this->examplePath, 1, 0);
        $this->assertSame([
            $this->examplePath . '/Helpers',
            $this->examplePath . '/Services',
            $this->examplePath . '/Services/MuchNicer',
            $this->examplePath . '/Services/Nice'
        ], $directories);
    }
}

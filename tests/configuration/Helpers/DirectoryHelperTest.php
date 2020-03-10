<?php
declare(strict_types=1);

namespace SilvermoonTests\Configuration\Helpers;

use Silvermoon\Configuration\Helpers\DirectoryHelper;
use Silvermoon\TestingFramework\BaseUnitTest;

class DirectoryHelperTest extends BaseUnitTest
{
    protected string $examplePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->examplePath = \realpath(\getcwd() . '/../Fixtures/FilesAndDirectoriesExample');
    }


    public function testLoadDirectories()
    {
        $directories = DirectoryHelper::directoriesInPath($this->examplePath);
        $this->assertSame(['Helpers', 'Services'], $directories);
    }

    public function testLoadDirectoriesInDepthTow()
    {
        $directories = DirectoryHelper::directoriesInPath($this->examplePath, 1);
        $this->assertSame(['Helpers', 'Services', 'Services/Nice'], $directories);
    }

    public function testLoadDirectoriesInDepthTowStartAtTow()
    {
        $directories = DirectoryHelper::directoriesInPath($this->examplePath, 1, 1);
        $this->assertSame(['Services/Nice'], $directories);
    }

    public function testLoadDirectoriesInDepthTowFull()
    {
        $directories = DirectoryHelper::directoriesInPath($this->examplePath, 1, 0, true);
        $this->assertSame([
            $this->examplePath . '/Helpers',
            $this->examplePath . '/Services',
            $this->examplePath . '/Services/Nice'
        ], $directories);
    }
}

<?php
declare(strict_types=1);

namespace SilvermoonTests\Configuration\Helpers;

use Silvermoon\Configuration\Helper\_FileHelper;
use Silvermoon\TestingFramework\BaseUnitTest;

class _FileHelperTest extends BaseUnitTest
{
    protected string $examplePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->examplePath = \realpath(\getcwd() . '/../Fixtures/vendor');
    }

    public function testFilesInPathDirectory()
    {
        $path = $this->examplePath . '/planet/jupiter/configuration';
        $files = _FileHelper::filesInPath($path);
        $this->assertCount(3, $files);
    }

    public function testFilesInPathDirectoryFileExtensionFilter()
    {
        $path = $this->examplePath . '/planet/jupiter/configuration';
        $files = _FileHelper::filesInPath($path, 'yaml');
        $this->assertCount(2, $files);
    }

    public function testFilesInPathWrongDirectory()
    {
        $path = $this->examplePath . '/planet/jupiter/configuration2';
        $files = _FileHelper::filesInPath($path);
        $this->assertCount(0, $files);
    }

    public function testFilesInPathDirectoryIsFile()
    {
        $path = $this->examplePath . '/planet/jupiter/configuration/Size.yaml';
        $files = _FileHelper::filesInPath($path);
        $this->assertCount(0, $files);
    }

    public function testFilesInPathDirectoryAbsolutePath()
    {
        $path = $this->examplePath . '/planet/jupiter/configuration';
        $files = _FileHelper::filesInPathAbsolutePath($path, null);
        $this->assertCount(3, $files);
        $this->assertSame($path . '/Color.txt', $files[0]);
        $this->assertSame($path . '/Size.yaml', $files[1]);
        $this->assertSame($path . '/Temperature.yaml', $files[2]);
    }
}

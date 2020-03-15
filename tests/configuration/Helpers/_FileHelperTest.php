<?php
declare(strict_types=1);

namespace SilvermoonTests\Configuration\Helpers;

use Silvermoon\Configuration\Exception\InvalidNameException;
use Silvermoon\Configuration\Helper\_FileHelper;
use Silvermoon\TestingFramework\BaseUnitTest;
use SilvermoonTests\Configuration\Proxies\Helper\ConfigMergeHelperProxy;

class _FileHelperTest extends BaseUnitTest
{


    public function testFilesInPathDirectory()
    {
        $path = getcwd() . '/../Fixtures/vendor/planet/jupiter/configuration';
        $files = _FileHelper::filesInPath($path);
        $this->assertCount(3, $files);
    }

    public function testFilesInPathDirectoryFileExtensionFilter()
    {
        $path = getcwd() . '/../Fixtures/vendor/planet/jupiter/configuration';
        $files = _FileHelper::filesInPath($path, 'yaml');
        $this->assertCount(2, $files);
    }

    public function testFilesInPathWrongDirectory()
    {
        $files = _FileHelper::filesInPath('dhfhdshgds');
        $this->assertCount(0, $files);
    }

    public function testFilesInPathDirectoryIsFile()
    {
        $path = getcwd() . '/../Fixtures/vendor/planet/jupiter/configuration/Size.yaml';
        $files = _FileHelper::filesInPath($path);
        $this->assertCount(0, $files);
    }
}
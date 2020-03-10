<?php
declare(strict_types=1);

namespace SilvermoonTests\Configuration\Helpers;

use Silvermoon\Configuration\Exception\InvalidNameException;
use Silvermoon\TestingFramework\BaseUnitTest;
use SilvermoonTests\Configuration\Proxies\Helper\ConfigMergeHelperProxy;

class ConfigMergeHelperTest extends BaseUnitTest
{
    public function testParseKeyInvalidNameStartWitchNumber()
    {
        $this->expectException(InvalidNameException::class);
        ConfigMergeHelperProxy::parseKey('8HelloWorld');
    }

    public function testParseKeyInvalidName()
    {
        $this->expectException(InvalidNameException::class);
        ConfigMergeHelperProxy::parseKey('_-HelloWorld');
    }

    public function testParseKeyInvalidDataType()
    {
        $this->expectException(InvalidNameException::class);
        ConfigMergeHelperProxy::parseKey('test<<<hallo>');
    }

    public function testParseKeyNoError()
    {
        $result = ConfigMergeHelperProxy::parseKey('_1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $this->assertIsArray($result);
    }

    public function testParseKeyArrayType()
    {
        $result = ConfigMergeHelperProxy::parseKey('items[string]');
        $this->assertIsArray($result);
    }

    public function testParseDataType()
    {
        $result = ConfigMergeHelperProxy::parseKey('items[Test\NewTool\Nice]');
        $this->assertIsArray($result);
        $this->assertSame('Test\NewTool\Nice', $result['dataType']);
    }

    public function testParseDataTypeArrayAdd()
    {
        $result = ConfigMergeHelperProxy::parseKey('items[+]');
        $this->assertIsArray($result);
        $this->assertSame('arrayAdd', $result['type']);
        $this->assertSame('+', $result['dataType']);
    }
}

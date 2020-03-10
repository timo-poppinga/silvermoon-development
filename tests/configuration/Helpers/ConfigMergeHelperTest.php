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

    public function testParseKeyNoError()
    {
        $result = ConfigMergeHelperProxy::parseKey('_1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $this->assertIsArray($result);
    }

    public function testParseKeyArrayType()
    {
        $result = ConfigMergeHelperProxy::parseKey('items[string]');
        $t = ConfigMergeHelperProxy::class;
        $this->assertIsArray($result);
    }
}

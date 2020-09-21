<?php
declare(strict_types=1);

namespace SilvermoonTests\Configuration\Internal\Utility;

use Silvermoon\Configuration\Exception\InvalidNameException;
use Silvermoon\Configuration\Helper\_ConfigMergeHelper;
use Silvermoon\TestingFramework\BaseUnitTest;
use SilvermoonTests\Configuration\Proxies\Utility\ConfigMergeHelperProxy;
use SilvermoonTests\Configuration\Proxies\Utility\ConfigurationMergeUtility;

class ConfigurationMergeUtilityTest extends BaseUnitTest
{
    public function testParseKeyInvalidNameStartWitchNumber()
    {
        $this->expectException(InvalidNameException::class);
        ConfigurationMergeUtility::parseKey('8HelloWorld');
    }

    public function testParseKeyInvalidName()
    {
        $this->expectException(InvalidNameException::class);
        ConfigurationMergeUtility::parseKey('_-HelloWorld');
    }

    public function testParseKeyInvalidDataType()
    {
        $this->expectException(InvalidNameException::class);
        ConfigurationMergeUtility::parseKey('test<<<hallo>');
    }

    public function testParseKeyNoError()
    {
        $result = ConfigurationMergeUtility::parseKey('_1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $this->assertIsArray($result);
    }

    public function testParseKeyArrayType()
    {
        $result = ConfigurationMergeUtility::parseKey('items[string]');
        $this->assertIsArray($result);
    }

    public function testParseDataType()
    {
        $result = ConfigurationMergeUtility::parseKey('items[Test\NewTool\Nice]');
        $this->assertIsArray($result);
        $this->assertSame('Test\NewTool\Nice', $result['dataType']);
    }

    public function testParseDataTypeArrayAdd()
    {
        $result = ConfigurationMergeUtility::parseKey('items[+]');
        $this->assertIsArray($result);
        $this->assertSame('arrayAdd', $result['type']);
        $this->assertSame('+', $result['dataType']);
    }

    /**
     * @throws InvalidNameException
     * @todo test needs to be done
     */
    public function toDoTestMergeConfigRecursive()
    {
        $configuration = [
            'config' => [
                'planets[string]' => [
                    'jupiter',
                    'mercury',
                    'neptune',
                    'pluto',
                ],
            ],
        ];
        $configurationToAdd = [
            'config' => [
                'stars' => 'Hallo'
            ]
        ];
        ConfigurationMergeUtility::_mergeConfigRecursive($configuration, $configurationToAdd);
        $configurationToAdd = [
            'config' => [
                'stars' => 'Hello World!'
            ]
        ];
        ConfigurationMergeUtility::_mergeConfigRecursive($configuration, $configurationToAdd);
        $this->assertSame([], $configuration);
    }
}

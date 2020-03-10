<?php
declare(strict_types=1);

namespace SilvermoonTests\Configuration;

use Silvermoon\Configuration\Configuration;
use Silvermoon\Configuration\Helpers\DirectoryHelper;
use Silvermoon\Configuration\Services\PackagesService;
use Silvermoon\TestingFramework\BaseUnitTest;
use SilvermoonTests\Injection\Fixtures\Display;
use SilvermoonTests\Injection\Fixtures\MultiDependencies;
use SilvermoonTests\Injection\Proxies\SimpleContainerProxy;

class ConfigurationTest extends BaseUnitTest
{
    protected Configuration $configuration;

    protected function setUp(): void
    {
        parent::setUp();
        $this->configuration = new Configuration();
    }

    public function testReadConfigurationAsStringNull()
    {
        $data = $this->configuration->readConfigurationAsString('Test01/Test03');
        $this->assertNull($data);
    }

    public function testReadConfigurationAsStringDefault()
    {
        $data =  $this->configuration->readConfigurationAsString('Test01/Test03', 'DefaultText');
        $this->assertSame('DefaultText', $data);
    }

    public function testReadConfigurationAsStringValue()
    {
        $this->configuration->setConfiguration('Test01/Test03', 'SetValue');
        $data = $this->configuration->readConfigurationAsString('Test01/Test03', 'DefaultText');
        $this->assertSame('SetValue', $data);
    }

    public function testReadConfigurationAsStringValueWrongType()
    {
        $this->configuration->setConfiguration('Test01/Test03', 100);
        $data = $this->configuration->readConfigurationAsString('Test01/Test03', 'DefaultText');
        $this->assertSame('DefaultText', $data);
    }
}
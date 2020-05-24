<?php
declare(strict_types=1);

namespace SilvermoonTests\Configuration\Helpers;

use Silvermoon\Configuration\Service\ConfigurationLoader;
use Silvermoon\TestingFramework\BaseUnitTest;

class ConfigurationLoaderTest extends BaseUnitTest
{
    /**
     * @var false|string
     */
    protected string $examplePath;

    /**
     * @var ConfigurationLoader
     */
    protected ConfigurationLoader $configurationLoader;

    protected function setUp(): void
    {
        parent::setUp();
        $this->examplePath = \realpath(\getcwd() . '/../Fixtures');
        $this->configurationLoader = new ConfigurationLoader();
    }

    public function testPackagesInPath()
    {
        $configuration = $this->configurationLoader->load($this->examplePath);
        $this->assertSame([], $configuration);
    }
}

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
        $currentWorkingDirectory = \getcwd();
        $realPath =  \realpath($currentWorkingDirectory . '/../Fixtures');
        if ($realPath === false) {
            $realPath = \realpath($currentWorkingDirectory . '/tests/configuration/Fixtures');
        }
        $this->examplePath = $realPath;
        $this->configurationLoader = new ConfigurationLoader();
    }

    /**
     * @throws \Silvermoon\Exception\System\FileNotFoundException
     * @throws \Silvermoon\Exception\System\PathNotFoundException
     * @todo implemt test
     */
    public function testPackagesInPath()
    {
        $configuration = $this->configurationLoader->load($this->examplePath);
        $this->assertSame([], []);
    }
}

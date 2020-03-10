<?php
declare(strict_types=1);

namespace SilvermoonTests\Configuration;

use Silvermoon\TestingFramework\BaseUnitTest;
use Symfony\Component\Yaml\Yaml;

class DevelopTest extends BaseUnitTest
{
    public function testReadYaml()
    {
        $r = getcwd();
        $data = Yaml::parse(file_get_contents('./Fixtures/configuration/TestConfig.yaml'));

        $keys01 = array_keys($data);
        $this->assertSame('', $data);
    }
}

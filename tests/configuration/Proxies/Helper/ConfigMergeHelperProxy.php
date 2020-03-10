<?php
declare(strict_types=1);

namespace SilvermoonTests\Configuration\Proxies\Helper;

use Silvermoon\Configuration\Configuration;
use Silvermoon\Configuration\Helper\ConfigMergeHelper;
use Silvermoon\Configuration\Helpers\DirectoryHelper;
use Silvermoon\Configuration\Services\PackagesService;
use Silvermoon\TestingFramework\BaseUnitTest;
use SilvermoonTests\Injection\Fixtures\Display;
use SilvermoonTests\Injection\Fixtures\MultiDependencies;
use SilvermoonTests\Injection\Proxies\SimpleContainerProxy;

class ConfigMergeHelperProxy extends ConfigMergeHelper
{
    public static function parseKey(string $key): array
    {
        return parent::parseKey($key);
    }
}
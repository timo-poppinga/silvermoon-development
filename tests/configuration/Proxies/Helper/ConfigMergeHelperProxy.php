<?php
declare(strict_types=1);

namespace SilvermoonTests\Configuration\Proxies\Helper;

use Silvermoon\Configuration\Helper\ConfigMergeHelper;

class ConfigMergeHelperProxy extends ConfigMergeHelper
{
    public static function parseKey(string $key): array
    {
        return parent::parseKey($key);
    }
}

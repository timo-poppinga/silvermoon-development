<?php
declare(strict_types=1);

namespace SilvermoonTests\Injection\Proxies;

use Silvermoon\Injection\SimpleContainer;

class SimpleContainerProxy extends SimpleContainer
{
    public function getInjectables(string $className, string $methodName = 'inject'): array
    {
        return parent::getInjectables($className, $methodName);
    }
}

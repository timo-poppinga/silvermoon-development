<?php
declare(strict_types=1);

namespace Silvermoon\Injection\Service;

use Silvermoon\Contracts\Injection\InjectorServiceInterface;

/**
 * Class DependencyInjectorService
 */
class DependencyInjectorService implements InjectorServiceInterface
{
    public function methodNameToInject(): string
    {
        return 'inject';
    }
}

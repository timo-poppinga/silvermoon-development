<?php
declare(strict_types=1);

namespace Silvermoon\Configuration\Service;

use Silvermoon\Contracts\Injection\ContainerInterface;
use Silvermoon\Contracts\Injection\InjectorServiceInterface;

/**
 * Class ConfigurationInjectorService
 * @package Silvermoon\Configuration\Service
 */
class ConfigurationInjectorService implements InjectorServiceInterface
{
    public function methodNameToInject(): string
    {
        return 'configuration';
    }

    public function injector(string $className, array $injectStruct, ContainerInterface $container): array
    {
        // TODO: Implement injector() method.
    }
}

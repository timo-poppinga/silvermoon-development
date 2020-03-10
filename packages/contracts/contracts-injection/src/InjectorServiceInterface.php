<?php
declare(strict_types=1);

namespace Silvermoon\Contracts\Injection;

/**
 * Interface InjectorServiceInterface
 */
interface InjectorServiceInterface
{
    /**
     * @return string
     */
    public function methodNameToInject(): string;

    /**
     * @param ContainerInterface $container
     * @param object $object
     * @param array[] $injectables
     */

    /**
     * @param string $className
     * @param array $injectables
     * @param ContainerInterface $container
     * @return mixed[]
     */
    public function injector(string $className, array $injectables, ContainerInterface $container): array;
}

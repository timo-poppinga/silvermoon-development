<?php
declare(strict_types=1);

namespace Silvermoon\Contracts\DependencyInjection;

/**
 * Interface ContainerInterface
 */
interface ContainerInterface
{
    /**
     * @param string $className
     * @param mixed[] ...$constructorArguments
     * @return object
     */
    public function get(string $className, ...$constructorArguments): object;

    /**
     * @param string $interfaceName
     * @return object|null
     */
    public function getByInterface(string $interfaceName): ?object;
}

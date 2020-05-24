<?php
declare(strict_types=1);

namespace Silvermoon\Contracts\Injection;

use Psr\Container\ContainerInterface as PsrContainerInterface;

/**
 * Interface ContainerInterface
 */
interface ContainerInterface extends PsrContainerInterface
{
    /**
     * @param string $className
     * @param mixed[] ...$constructorArguments
     * @return object
     */
    public function getByClassName(string $className, ...$constructorArguments): object;

    /**
     * @param string $interfaceName
     * @return object|null
     */
    public function getByInterfaceName(string $interfaceName): ?object;
}

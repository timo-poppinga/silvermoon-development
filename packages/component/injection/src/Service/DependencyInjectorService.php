<?php
declare(strict_types=1);

namespace Silvermoon\Injection\Service;

use Psr\Container\ContainerInterface as PsrContainerInterface;
use Silvermoon\Contracts\Injection\ContainerInterface;
use Silvermoon\Contracts\Injection\InjectorServiceInterface;
use Silvermoon\Exception\ConfigurationException;
use Silvermoon\Injection\Exception\ImplementationDoesNotExistsException;
use Silvermoon\Injection\Exception\WrongTypeException;

/**
 * Class DependencyInjectorService
 */
class DependencyInjectorService implements InjectorServiceInterface
{
    /**
     * @return string
     */
    public function methodNameToInject(): string
    {
        return 'inject';
    }

    /**
     * @param string $className
     * @param array[] $injectStruct
     * @param ContainerInterface $container
     * @return array<mixed>
     * @throws ConfigurationException
     * @throws ImplementationDoesNotExistsException
     * @throws WrongTypeException
     */
    public function injector(string $className, array $injectStruct, ContainerInterface $container): array
    {
        $injectableObjects = [];
        foreach ($injectStruct as $injectable) {
            if ($injectable['type'] !== 'class') {
                throw new WrongTypeException('The injectable must be an class or interface');
            }
            $interfaceClassName = $injectable['dependency'];
            $optional = $injectable['optional'];
            if ($interfaceClassName === ContainerInterface::class) {
                $injectableObjects[] = $container;
                continue;
            }
            if ($interfaceClassName === PsrContainerInterface::class) {
                $injectableObjects[] = $container;
                continue;
            }
            if (\interface_exists($interfaceClassName)) {
                $injectableObject = $container->getByInterfaceName($interfaceClassName);
                if ($injectableObject === null && $optional === false) {
                    throw new ImplementationDoesNotExistsException('No Implementation for the interface ' . $interfaceClassName . ' dependency does not exists. Please register.');
                }
                $injectableObjects[] = $injectableObject;
                continue;
            }

            if (!\class_exists($interfaceClassName)) {
                throw new ConfigurationException('No class ' . $interfaceClassName . ' does not exists.');
            }

            $injectableObjects[] = $container->getByClassName($interfaceClassName);
        }

        return $injectableObjects;
    }
}

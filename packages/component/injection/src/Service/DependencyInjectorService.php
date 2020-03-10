<?php
declare(strict_types=1);

namespace Silvermoon\Injection\Service;

use Silvermoon\Contracts\Injection\ContainerInterface;
use Silvermoon\Contracts\Injection\InjectorServiceInterface;
use Silvermoon\Injection\Exception\ConfigurationException;
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
     * @param ContainerInterface $container
     * @param array[] $injectables
     * @return mixed[]
     * @throws ConfigurationException
     * @throws ImplementationDoesNotExistsException
     * @throws WrongTypeException
     */
    public function injector(ContainerInterface $container, array $injectables): array
    {
        $injectableObjects = [];
        foreach ($injectables as $injectable) {
            if ($injectable['type'] !== 'class') {
                throw new WrongTypeException('The injectable must be an class or interface');
            }
            $interfaceClassName = $injectable['dependency'];
            $optional = $injectable['optional'];
            if ($interfaceClassName === ContainerInterface::class) {
                $dependObjects[] = $this;
                continue;
            }
            if (\interface_exists($interfaceClassName)) {
                $injectableObject = $container->getByInterface($interfaceClassName);
                if ($injectableObject === null && $optional === false) {
                    throw new ImplementationDoesNotExistsException('No Implementation for the interface ' . $interfaceClassName . ' dependency does not exists. Please register.');
                }
                $injectableObjects[] = $injectableObject;
                continue;
            }

            if (!\class_exists($interfaceClassName)) {
                throw new ConfigurationException('No class ' . $interfaceClassName . ' does not exists.');
            }

            $injectableObjects[] = $container->get($interfaceClassName);
        }

        return $injectableObjects;
    }
}

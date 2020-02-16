<?php
declare(strict_types=1);

namespace Silvermoon\Injection;

use Silvermoon\Contracts\Injection\ContainerInterface;
use Silvermoon\Contracts\Injection\SingletonInterface;
use Silvermoon\Injection\Exception\ClassDoesNotExistsException;
use Silvermoon\Injection\Exception\ImplementationDoesNotExistsException;
use Silvermoon\Injection\Exception\InterfaceDoesNotExistsException;
use Silvermoon\Injection\Exceptionbin\WrongTypeException;

/**
 * Class SimpleContainer
 */
class SimpleContainer implements ContainerInterface
{
    /**
     * @var string[]
     */
    protected array $mapInterfaceToClass = [];

    /**
     * @var object[]
     */
    protected array $singletonArray = [];


    public function get(string $className, ...$constructorArguments): object
    {
        if (\array_key_exists($className, $this->singletonArray)) {
            return $this->singletonArray[$className];
        }
        if (!\class_exists($className)) {
            throw new ClassDoesNotExistsException('The Class ' . $className . ' does not exists.');
        }

        $dependencies = $this->getDependencies($className);

        if (count($dependencies) === 0) {
            $newObject = new $className(...$constructorArguments);
            $this->checkForSingletonInterface($className, $newObject);
            return $newObject;
        }

        $dependObjects = [];
        foreach ($dependencies as $dependency) {
            $interfaceClassName = $dependency['dependency'];
            $optional = $dependency['optional'];

            if ($interfaceClassName === \Z3\Contracts\DependencyInjection\ContainerInterface::class) {
                $dependObjects[] = $this;
                continue;
            }

            if (\interface_exists($interfaceClassName)) {
                $object = $this->getByInterface($interfaceClassName);
                if ($object === null && $optional === false) {
                    throw new ImplementationDoesNotExistsException('No Implementation for the interface ' . $interfaceClassName . ' dependency does not exists. Please register.');
                }
                $dependObjects[] = $object;
                continue;
            }

            if (!\class_exists($interfaceClassName)) {
                throw new ClassDoesNotExistsException('No class ' . $interfaceClassName . ' does not exists.');
            }

            $dependObjects[] = $this->get($interfaceClassName);
        }

        $newObject = new $className(...$constructorArguments);
        $newObject->inject(...$dependObjects);
        $this->checkForSingletonInterface($className, $newObject);
        return $newObject;
    }

    /**
     * @param string $className
     * @param object $newObject
     */
    private function checkForSingletonInterface(string $className, object $newObject): void
    {
        if ($this->implementsInterface(SingletonInterface::class, $className)) {
            $this->singletonArray[$className] = $newObject;
        }
    }

    public function getByInterface(string $interfaceName): ?object
    {
        if (!\array_key_exists($interfaceName, $this->mapInterfaceToClass)) {
            return null;
        }
        $className = $this->mapInterfaceToClass[$interfaceName];
        return $this->get($className);
    }

    /**
     * @param string $className
     * @return array[]
     * @throws WrongTypeException
     */
    private function getDependencies(string $className): array
    {
        try {
            $reflectionClass = new \ReflectionClass($className);
        } catch (\ReflectionException $e) {
            return [];
        }
        $hasInjectMethod = $reflectionClass->hasMethod('inject');
        if ($hasInjectMethod === false) {
            return [];
        }
        try {
            $injectionMethod = $reflectionClass->getMethod('inject');
        } catch (\ReflectionException $e) {
            return [];
        }
        $out = [];
        /** @var \ReflectionParameter $reflectionParameter */
        foreach ($injectionMethod->getParameters() as $reflectionParameter) {
            $info = [];
            $info['name'] = $reflectionParameter->getName();
            $interfaceClass = $reflectionParameter->getClass();
            if ($interfaceClass === null) {
                throw new WrongTypeException('The parameter ' . $info['name'] . ' of inject method in class ' . $className . ' must be an interface or class.');
            }

            $info['dependency'] = $interfaceClass->getName();
            $info['optional'] = false;
            if ($reflectionParameter->getType() !== null) {
                $info['optional'] = $reflectionParameter->getType()->allowsNull();
            }

            $out[] = $info;
        }
        return $out;
    }

    /**
     * @param string $interfaceName
     * @param string $className
     * @throws ClassDoesNotExistsException
     * @throws InterfaceDoesNotExistsException
     * @throws WrongTypeException
     */
    public function register(string $interfaceName, string $className): void
    {
        if (!\interface_exists($interfaceName)) {
            throw new InterfaceDoesNotExistsException('Given interface <' . $interfaceName . '> not found or is not an interface');
        }
        if (!\class_exists($className)) {
            throw new ClassDoesNotExistsException('Given class <' . $className . '> not found');
        }
        if ($this->implementsInterface($interfaceName, $className) === false) {
            throw new WrongTypeException('Given class <' . $className . '> must implement the interface <' . $interfaceName . '>');
        }
        $this->mapInterfaceToClass[$interfaceName] = $className;
    }

    /**
     * @param string $interfaceName
     * @param string $className
     * @return bool
     */
    private function implementsInterface(string $interfaceName, string $className): bool
    {
        $interfaces = \class_implements($className, true);
        return \in_array($interfaceName, $interfaces, true);
    }
}

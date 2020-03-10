<?php
declare(strict_types=1);

namespace Silvermoon\Injection;

use Silvermoon\Contracts\Injection\ContainerInterface;
use Silvermoon\Contracts\Injection\InjectorServiceInterface;
use Silvermoon\Contracts\Injection\SingletonInterface;
use Silvermoon\Injection\Exception\ImplementationDoesNotExistsException;
use Silvermoon\Injection\Exception\InterfaceDoesNotExistsException;
use Silvermoon\Injection\Exception\WrongTypeException;
use Silvermoon\Injection\Service\DependencyInjectorService;

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

    /**
     * @var InjectorServiceInterface[]
     */
    private array $injectorServices = [];

    public function __construct()
    {
        $this->injectorServices[] = new DependencyInjectorService();
    }

    /**
     * @param string $interfaceName
     * @return object|null
     * @throws ImplementationDoesNotExistsException
     * @throws ImplementationDoesNotExistsException
     * @throws WrongTypeException
     */
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
     * @param mixed ...$constructorArguments
     * @return object
     * @throws ImplementationDoesNotExistsException
     * @throws ImplementationDoesNotExistsException
     * @throws WrongTypeException
     */
    public function get(string $className, ...$constructorArguments): object
    {
        if (\array_key_exists($className, $this->singletonArray)) {
            return $this->singletonArray[$className];
        }
        if (!\class_exists($className)) {
            throw new ImplementationDoesNotExistsException('The Class ' . $className . ' does not exists.');
        }
        $newObject = new $className(...$constructorArguments);
        /** @var  InjectorServiceInterface $injectorService */
        foreach ($this->injectorServices as $injectorService) {
            $methodNameToInject = $injectorService->methodNameToInject();
            $injectables = $this->getInjectables($className, $methodNameToInject);
            if (\count($injectables) === 0) {
                continue;
            }
            $injectableObjects = $injectorService->injector($className, $injectables, $this);
            $newObject->$methodNameToInject(...$injectableObjects);
        }

        $this->checkForSingletonInterface($className, $newObject);
        return $newObject;
    }

    /**
     * @param string $interfaceName
     * @param string $className
     * @throws ImplementationDoesNotExistsException
     * @throws InterfaceDoesNotExistsException
     * @throws WrongTypeException
     */
    public function register(string $interfaceName, string $className): void
    {
        if (!\interface_exists($interfaceName)) {
            throw new InterfaceDoesNotExistsException('Given interface <' . $interfaceName . '> not found or is not an interface');
        }
        if (!\class_exists($className)) {
            throw new ImplementationDoesNotExistsException('Given class <' . $className . '> not found');
        }
        if ($this->implementsInterface($interfaceName, $className) === false) {
            throw new WrongTypeException('Given class <' . $className . '> must implement the interface <' . $interfaceName . '>');
        }
        $this->mapInterfaceToClass[$interfaceName] = $className;
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


    /**
     * @param class-string $className
     * @param string $methodName
     * @return array[]
     */
    protected function getInjectables(string $className, string $methodName = 'inject'): array
    {
        try {
            $reflectionClass = new \ReflectionClass($className);
        } catch (\ReflectionException $e) {
            return [];
        }
        $hasInjectMethod = $reflectionClass->hasMethod($methodName);
        if ($hasInjectMethod === false) {
            return [];
        }
        try {
            $injectionMethod = $reflectionClass->getMethod($methodName);
        } catch (\ReflectionException $e) {
            return [];
        }
        $out = [];
        $reflectionParameters = $injectionMethod->getParameters();
        /** @var \ReflectionParameter $reflectionParameter */
        foreach ($reflectionParameters as $reflectionParameter) {
            $info = [];
            $interfaceClass = $reflectionParameter->getClass();
            $type = $reflectionParameter->getType();
            $info['name'] = $reflectionParameter->getName();
            $info['type'] = (string) $type;
            $info['optional'] = false;
            if ($interfaceClass !== null) {
                $info['type'] =  'class';
                $info['dependency'] = $interfaceClass->getName();
            }
            if ($type !== null) {
                $info['optional'] = $type->allowsNull();
            }
            if($reflectionParameter->isDefaultValueAvailable()) {
                try {
                    $info['defaultValue'] = $reflectionParameter->getDefaultValue();
                } catch (\ReflectionException $e) {}
            }
            $out[] = $info;
        }
        return $out;
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

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
use Silvermoon\Injection\Struct\Reflection\Method;
use Silvermoon\Injection\Utility\ReflectionUtility;

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

    protected ReflectionUtility $reflectionUtility;

    /**
     * @var InjectorServiceInterface[]
     */
    private array $injectorServices = [];

    public function __construct()
    {
        $this->injectorServices[] = new DependencyInjectorService();
        $this->reflectionUtility = new ReflectionUtility();
    }

    /**
     * @param string $id
     * @return mixed|object|null
     * @throws ImplementationDoesNotExistsException
     * @throws WrongTypeException
     */
    public function get($id)
    {
        if (\interface_exists($id) === true) {
            return $this->getByInterfaceName($id);
        }
        return $this->getByClassName($id);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id)
    {
        if (\interface_exists($id) === true) {
            return \array_key_exists($id, $this->mapInterfaceToClass);
        }
        return \class_exists($id);
    }

    /**
     * @param string $interfaceName
     * @return object|null
     * @throws ImplementationDoesNotExistsException
     * @throws ImplementationDoesNotExistsException
     * @throws WrongTypeException
     */
    public function getByInterfaceName(string $interfaceName): ?object
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
    public function getByClassName(string $className, ...$constructorArguments): object
    {
        if (\array_key_exists($className, $this->singletonArray)) {
            return $this->singletonArray[$className];
        }
        if (!\class_exists($className)) {
            throw new ImplementationDoesNotExistsException('The Class ' . $className . ' does not exists.');
        }
        $newObject = new $className(...$constructorArguments);

        foreach ($this->injectorServices as $injectorService) {
            $methodNameToInject = $injectorService->methodNameToInject();
            $injectables = $this->readInjectables($className, $methodNameToInject);
            foreach ($injectables as $injectable) {
                $injectStruct = $injectable['injectStruct'];
                $methodName = $injectable['methodName'];
                $injectableObjects = $injectorService->injector($className, $injectStruct, $this);
                $newObject->$methodName(...$injectableObjects);
            }
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
     * @param string $className
     * @param string $prefixMethodName
     * @return array[]
     * @throws Exception\ReflectionParseException
     */
    protected function readInjectables(string $className, string $prefixMethodName = 'inject'): array
    {
        $injectables = [];
        $reflection = $this->reflectionUtility->parseClass($className);
        $method = null;
        /** @var Method $currentMethod */
        foreach ($reflection->methods as $currentMethod) {
            if (strpos($currentMethod->name, $prefixMethodName) !== 0) {
                continue;
            }
            $injectStruct = $this->readInjectStruct($currentMethod);
            if (\count($injectStruct) === 0) {
                continue;
            }
            $injectables[] = [
                'methodName' => $currentMethod->name,
                'injectStruct' => $injectStruct,
            ];
        }
        return $injectables;
    }

    /**
     * @param Method $method
     * @return array[]
     */
    protected function readInjectStruct(Method $method): array
    {
        $injectStruct = [];
        foreach ($method->parameters as $parameter) {
            $info = [];
            $info['name'] = $parameter->name;
            $info['type'] = $parameter->type;
            $info['optional'] = $parameter->isAllowsNull;
            if ($parameter->isDefaultValueAvailable) {
                $info['defaultValue'] = $parameter->defaultValue;
            }
            if ($parameter->isBuiltin === false) {
                $info['type'] =  'class';
                $info['dependency'] = $parameter->type;
            }
            $injectStruct[] = $info;
        }
        return $injectStruct;
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

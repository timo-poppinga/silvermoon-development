<?php
declare(strict_types=1);

namespace Silvermoon\Injection\Utility;

use Silvermoon\Contracts\Injection\SingletonInterface;


use Silvermoon\Injection\Exception\ReflectionParseException;
use Silvermoon\Injection\Struct\Reflection;
use Silvermoon\Injection\Struct\Reflection\Method;
use Silvermoon\Injection\Struct\Reflection\Variable;

class ReflectionUtility implements SingletonInterface
{

    /**
     * @param string $className
     * @return Reflection
     * @throws ReflectionParseException
     */
    public function parseClass(string $className): Reflection
    {
        if (\class_exists($className) === false) {
            throw new ReflectionParseException('The <' . $className . '> dose not exists');
        }

        try {
            $reflectionClass = new \ReflectionClass($className);
        } catch (\ReflectionException $exception) {
            throw new ReflectionParseException('The <' . $className . '> can not be load for reflection', 0, $exception);
        }

        $reflection = new Reflection();
        $reflection->name = $className;
        $this->parseParent($reflection, $className);
        $this->parseInterface($reflection, $className);
        $this->parseMethods($reflection, $reflectionClass->getMethods());

        return $reflection;
    }


    protected function parseParent(Reflection $reflection, string $className): void
    {
        $parent = \get_parent_class($className);
        if ($parent === false) {
            return;
        }
        $reflection->parents[] = $parent;
        $this->parseParent($reflection, $parent);
    }

    protected function parseInterface(Reflection $reflection, string $className): void
    {
        $interfaces = \class_implements($className);
        foreach ($interfaces as $interface) {
            $reflection->interfaces[] = $interface;
        }
    }

    /**
     * @param Reflection $reflection
     * @param \ReflectionMethod[] $reflectionMethods
     */
    protected function parseMethods(Reflection $reflection, array $reflectionMethods): void
    {
        foreach ($reflectionMethods as $reflectionMethod) {
            $method = new Method();
            $method->name = $reflectionMethod->getName();

            /** @var \ReflectionNamedType $returnType */
            $returnType = $reflectionMethod->getReturnType();
            $method->return = null;
            if ($returnType !== null) {
                $method->return = $this->buildVariableStructForReturn($returnType);
            }

            $reflectionParameters = $reflectionMethod->getParameters();
            foreach ($reflectionParameters as $reflectionParameter) {
                $method->parameters[] =  $this->buildVariableForParameter($reflectionParameter);
            }
            $reflection->methods[] = $method;
        }
    }


    /**
     * @param \ReflectionParameter $parameter
     * @return Variable
     */
    protected function buildVariableForParameter(\ReflectionParameter $parameter): Variable
    {
        $variable = new Variable();
        $variable->name = $parameter->getName();
        $type = $parameter->getType();
        if ($type !== null) {
            /** @var @phpstan-ignore-next-line the method getName exists */
            $variable->type = $type->getName();
            $variable->isBuiltin = $type->isBuiltin();
            $variable->isAllowsNull = $type->allowsNull();
        }
        $variable->isDefaultValueAvailable = $parameter->isDefaultValueAvailable();
        if ($variable->isDefaultValueAvailable) {
            $variable->defaultValue = $parameter->getDefaultValue();
        }
        return $variable;
    }

    /**
     * @param \ReflectionNamedType $returnType
     * @return Variable|null
     */
    protected function buildVariableStructForReturn(\ReflectionNamedType $returnType): ?Variable
    {
        $type = $returnType->getName();
        if ($type === 'void') {
            return null;
        }
        $variable = new Variable();
        $variable->type = $returnType->getName();
        $variable->isAllowsNull  = $returnType->allowsNull();
        $variable->isBuiltin  = $returnType->isBuiltin();
        return $variable;
    }
}

<?php
declare(strict_types=1);

namespace SilvermoonTests\Injection\Utility;

use Silvermoon\Injection\Exception\ReflectionParseException;
use Silvermoon\Injection\Struct\Reflection;
use Silvermoon\Injection\Utility\ReflectionUtility;
use Silvermoon\TestingFramework\BaseUnitTest;
use SilvermoonTests\Injection\Fixtures\AbstractComplexObject;
use SilvermoonTests\Injection\Fixtures\BaseComplexObject;
use SilvermoonTests\Injection\Fixtures\ComplexObject;
use SilvermoonTests\Injection\Fixtures\ComplexObjectInterface;

class ReflectionUtilityTest extends BaseUnitTest
{
    protected ReflectionUtility $reflectionUtility;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reflectionUtility = new ReflectionUtility();
    }

    public function testParseStructure()
    {
        $reflection = $this->reflectionUtility->parseClass(ComplexObject::class);

        self::assertSame(ComplexObject::class, $reflection->name);

        self::assertCount(2, $reflection->parents);
        self::assertSame(BaseComplexObject::class, $reflection->parents[0]);
        self::assertSame(AbstractComplexObject::class, $reflection->parents[1]);

        self::assertCount(1, $reflection->interfaces);
        self::assertSame(ComplexObjectInterface::class, $reflection->interfaces[0]);


        self::assertCount(6, $reflection->methods);

        /** @var Reflection\Method $methodParseValues */
        $methodParseValues = $reflection->methods[5];
        self::assertSame('parseValues', $methodParseValues->name);
        self::assertCount(1, $methodParseValues->parameters);
        /** @var Reflection\Variable $parameter */
        $parameter = $methodParseValues->parameters[0];
        self::assertSame('name', $parameter->name);
        self::assertSame('string', $parameter->type);
        self::assertSame(true, $parameter->isBuiltin);
        self::assertSame(false, $parameter->isAllowsNull);
        self::assertSame(true, $parameter->isDefaultValueAvailable);
        self::assertSame('Max', $parameter->defaultValue);


        self::assertInstanceOf(Reflection::class, $reflection);
    }

    public function testParseStructureException()
    {
        self::expectException(ReflectionParseException::class);
        $this->reflectionUtility->parseClass(ComplexObject::class . 'No');
    }
}

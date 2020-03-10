<?php
declare(strict_types=1);

namespace SilvermoonTests\Injection;

use Silvermoon\TestingFramework\BaseUnitTest;
use SilvermoonTests\Injection\Fixtures\Display;
use SilvermoonTests\Injection\Fixtures\MultiDependencies;
use SilvermoonTests\Injection\Proxies\SimpleContainerProxy;

class GetDependenciesTest extends BaseUnitTest
{
    /**
     * @var SimpleContainerProxy
     */
    protected SimpleContainerProxy $simpleContainer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->simpleContainer = new SimpleContainerProxy();
    }


    public function testInjectNothing()
    {
        $out = $this->simpleContainer->getInjectables(MultiDependencies::class, 'injectNothing');
        $this->assertCount(0, $out);
    }

    public function testInjectNothingNoMethod()
    {
        $out = $this->simpleContainer->getInjectables(MultiDependencies::class, 'injectNothingNoMethod');
        $this->assertCount(0, $out);
    }

    public function testInjectNullableInterface()
    {
        $out = $this->simpleContainer->getInjectables(MultiDependencies::class, 'injectNullableInterface');
        $this->assertCount(1, $out);

        $first = $out[0];

        $this->assertSame('scoreService', $first['name']);
        $this->assertSame('class', $first['type']);
        $this->assertSame('SilvermoonTests\Injection\Fixtures\Service\ScoreServiceInterface', $first['dependency']);
        $this->assertSame(true, $first['optional']);
    }

    public function testInjectMulti()
    {
        $out = $this->simpleContainer->getInjectables(MultiDependencies::class, 'injectMulti');
        $this->assertCount(2, $out);
        $part01 = $out[0];
        $part02 = $out[1];
        $this->assertCount(4, $part01);
        $this->assertCount(4, $part02);

        $this->assertSame('scoreService', $part01['name']);
        $this->assertSame('class', $part01['type']);
        $this->assertSame('SilvermoonTests\Injection\Fixtures\Service\ScoreServiceInterface', $part01['dependency']);
        $this->assertSame(true, $part01['optional']);

        $this->assertSame('display', $part02['name']);
        $this->assertSame('class', $part02['type']);
        $this->assertSame(Display::class, $part02['dependency']);
        $this->assertSame(false, $part02['optional']);
    }



    public function testInjectBaseType()
    {
        $out = $this->simpleContainer->getInjectables(MultiDependencies::class, 'injectBaseType');
        $this->assertCount(2, $out);
        $part01 = $out[0];
        $part02 = $out[1];
        $this->assertCount(3, $part01);
        $this->assertCount(4, $part02);

        $this->assertSame('info01', $part01['name']);
        $this->assertSame('string', $part01['type']);
        $this->assertSame(false, $part01['optional']);

        $this->assertSame('info02', $part02['name']);
        $this->assertSame('string', $part02['type']);
        $this->assertSame(true, $part02['optional']);
        $this->assertSame('hello', $part02['defaultValue']);
    }

    public function testInjectMultiBaseType()
    {
        $out = $this->simpleContainer->getInjectables(MultiDependencies::class, 'injectMultiBaseType');
        $this->assertCount(3, $out);
        $part01 = $out[0];
        $part02 = $out[1];
        $part03 = $out[2];
        $this->assertCount(3, $part01);
        $this->assertCount(3, $part02);
        $this->assertCount(3, $part03);


        $this->assertSame('info01', $part01['name']);
        $this->assertSame('string', $part01['type']);
        $this->assertSame(false, $part01['optional']);

        $this->assertSame('info02', $part02['name']);
        $this->assertSame('array', $part02['type']);
        $this->assertSame(false, $part02['optional']);

        $this->assertSame('info03', $part03['name']);
        $this->assertSame('int', $part03['type']);
        $this->assertSame(true, $part03['optional']);
    }
}

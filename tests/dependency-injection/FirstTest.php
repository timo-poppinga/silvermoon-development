<?php
declare(strict_types=1);

namespace SilvermoonTests\DependencyInjection;

use Silvermoon\TestingFramework\BaseUnitTest;

class FirstTest extends BaseUnitTest
{
    public function testFirst()
    {
        $this->assertSame('Hello', 'Hello');
    }
}

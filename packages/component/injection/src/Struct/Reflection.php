<?php
declare(strict_types=1);

namespace Silvermoon\Injection\Struct;

use Silvermoon\Injection\Struct\Reflection\Method;

class Reflection
{
    public string $name;

    /**
     * @var string[]
     */
    public array $parents = [];

    /**
     * @var string[]
     */
    public array $interfaces = [];

    /**
     * @var Method[]
     */
    public array $methods = [];
}

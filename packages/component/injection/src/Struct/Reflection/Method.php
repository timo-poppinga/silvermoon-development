<?php
declare(strict_types=1);

namespace Silvermoon\Injection\Struct\Reflection;

class Method
{
    public string $name;

    /**
     * @var Variable[]
     */
    public array $parameters = [];

    public ?Variable $return = null;
}

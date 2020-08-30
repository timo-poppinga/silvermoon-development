<?php
declare(strict_types=1);

namespace Silvermoon\Injection\Struct\Reflection;

class Variable
{
    public ?string $name = null;

    public ?string $type = null;

    public ?bool $isAllowsNull = null;

    public ?bool $isBuiltin = null;

    public bool $isDefaultValueAvailable = false;

    /**
     * @phpstan-ignore-next-line default value can all base values
     */
    public $defaultValue;
}

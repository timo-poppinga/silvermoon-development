<?php
declare(strict_types=1);

namespace Silvermoon\Exception\Struct;

class Trace
{
    public string $file;

    public int $line;

    public string $function;

    public string $class;
}

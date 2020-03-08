<?php
declare(strict_types=1);

namespace Silvermoon\Contracts\Injection;

/**
 * Interface InjectorServiceInterface
 */
interface InjectorServiceInterface
{
    /**
     * @return string
     */
    public function methodNameToInject(): string;
}

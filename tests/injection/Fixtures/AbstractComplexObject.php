<?php
declare(strict_types=1);

namespace SilvermoonTests\Injection\Fixtures;

use SilvermoonTests\Injection\Fixtures\Service\ScoreServiceInterface;

abstract class AbstractComplexObject implements ComplexObjectInterface
{
    public function run(): void
    {
    }
}

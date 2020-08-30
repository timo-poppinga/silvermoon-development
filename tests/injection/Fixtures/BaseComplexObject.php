<?php
declare(strict_types=1);

namespace SilvermoonTests\Injection\Fixtures;

use SilvermoonTests\Injection\Fixtures\Service\ScoreServiceInterface;

class BaseComplexObject extends AbstractComplexObject
{
    public function parseValues(string $name = 'Max'): string
    {
    }
}

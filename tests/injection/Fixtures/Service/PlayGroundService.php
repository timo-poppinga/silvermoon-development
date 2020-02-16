<?php
declare(strict_types=1);

namespace SilvermoonTests\Injection\Fixtures\Service;

use Silvermoon\Contracts\Injection\SingletonInterface;

class PlayGroundService implements SingletonInterface
{
    public int $count = 10;
}

<?php
declare(strict_types=1);

namespace SilvermoonTests\Injection\Fixtures;

use SilvermoonTests\Injection\Fixtures\Service\ScoreServiceInterface;

class MultiDependencies
{
    public function injectNothing()
    {
    }

    public function injectNullableInterface(?ScoreServiceInterface $scoreService)
    {
    }

    public function injectMulti(?ScoreServiceInterface $scoreService, Display $display)
    {
    }

    public function injectBaseType(string $info01, ?string $info02 = 'hello')
    {
    }

    public function injectMultiBaseType(string $info01, array $info02, ?int $info03)
    {
    }
}

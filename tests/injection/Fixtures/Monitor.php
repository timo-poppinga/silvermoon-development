<?php
declare(strict_types=1);

namespace SilvermoonTests\Injection\Fixtures;

use SilvermoonTests\Injection\Fixtures\Service\ScoreServiceInterface;

class Monitor
{
    public ?ScoreServiceInterface $scoreService;

    public function inject(?ScoreServiceInterface $scoreService)
    {
        $this->scoreService = $scoreService;
    }
}

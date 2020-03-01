<?php
declare(strict_types=1);

namespace SilvermoonTests\Injection\Fixtures;

use SilvermoonTests\Injection\Fixtures\Service\ScoreServiceInterface;

class Display
{
    public ScoreServiceInterface $scoreService;

    public function inject(ScoreServiceInterface $scoreService)
    {
        $this->scoreService = $scoreService;
    }
}

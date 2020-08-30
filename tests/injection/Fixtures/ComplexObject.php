<?php
declare(strict_types=1);

namespace SilvermoonTests\Injection\Fixtures;

use SilvermoonTests\Injection\Fixtures\Service\ScoreServiceInterface;

class ComplexObject extends BaseComplexObject
{
    public function inject(ScoreServiceInterface $scoreService): void
    {
    }

    public function run(): void
    {
    }

    public function getName(): string
    {
    }

    public function getDisplay(): Display
    {
    }

    public function getMonitor(): ?Monitor
    {
    }
}

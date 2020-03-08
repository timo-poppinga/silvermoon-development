<?php
declare(strict_types=1);

namespace SilvermoonTests\Injection\Fixtures;

class GreenDisplay
{
    public Display $display;

    public function inject(Display $display)
    {
        $this->display = $display;
    }
}

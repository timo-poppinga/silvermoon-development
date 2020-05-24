<?php
declare(strict_types=1);

namespace SilvermoonTests\Injection\Fixtures\Service;

use Psr\Container\ContainerInterface as PsrContainerInterface;
use Silvermoon\Contracts\Injection\ContainerInterface;

class ToolWithContainerService
{
    public ContainerInterface $container;

    public ContainerInterface $psrContainer;

    public function inject(ContainerInterface $container, PsrContainerInterface $psrContainer)
    {
        $this->container = $container;
        $this->psrContainer = $psrContainer;
    }
}

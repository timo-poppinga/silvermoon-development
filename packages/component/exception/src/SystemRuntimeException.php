<?php
declare(strict_types=1);

namespace Silvermoon\Exception;

use Silvermoon\Contracts\Exception\SystemRuntimeExceptionInterface;

class SystemRuntimeException extends \RuntimeException implements SystemRuntimeExceptionInterface
{
}

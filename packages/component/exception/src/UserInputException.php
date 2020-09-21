<?php
declare(strict_types=1);

namespace Silvermoon\Exception;

use Silvermoon\Contracts\Exception\UserInputExceptionInterface;

class UserInputException extends \InvalidArgumentException implements UserInputExceptionInterface
{
}

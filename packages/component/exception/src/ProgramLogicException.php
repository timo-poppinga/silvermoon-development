<?php
declare(strict_types=1);

namespace Silvermoon\Exception;

use Silvermoon\Contracts\Exception\ProgramLogicExceptionInterface;
use Silvermoon\Exception\Struct\Trace;

class ProgramLogicException extends \LogicException implements ProgramLogicExceptionInterface
{
    protected array $trace;

    /**
     * ProgramLogicException constructor.
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(int $code, ?\Throwable $previous = null)
    {
        parent::__construct(\get_class($this), $code, $previous);
        $this->trace = \debug_backtrace();
    }


    public function getTrigger(): Trace
    {
        return $this->buildTrigger();
    }

    protected function buildTrigger(): Trace
    {
        $trigger = new Trace();

        $trace = $this->getTrace();

        $trace = $trace[0];

        $trigger->file = $trace['file'];
        $trigger->line = $trace['line'];
        $trigger->function = $trace['function'];
        $trigger->class = $trace['class'];

        return $trigger;
    }
}

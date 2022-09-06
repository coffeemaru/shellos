<?php

namespace Coffeemaru\Shellos;

use Coffeemaru\Shellos\Executors\PHPExecutor;

class ShellCommand
{
    private int $result;
    private array $output;
    private string $command;

    protected SystemExecutor $executor;
    protected static SystemExecutor $default_executor;

    public function __construct(string $command)
    {
        $this->command = $command;
    }

    public function execute(): bool
    {
        $this->output = [];
        $executor = $this->getExecutor();
        $this->result = $executor->execute($this->command, $this->output);
        return $this->result === 0;
    }

    public function getOutputLines(): array
    {
        return $this->output;
    }

    public function getOutputString(): string
    {
        return implode("\n", $this->output);
    }

    public function setExecutor(SystemExecutor $executor): void
    {
        $this->executor = $executor;
    }

    public static function setDefaultExecutor(SystemExecutor $executor): void
    {
        static::$default_executor = $executor;
    }

    public function getExecutor(): SystemExecutor
    {
        if (!isset($this->executor)) {
            if (isset(static::$default_executor)) {
                $this->executor = static::$default_executor;
            } else {
                $this->executor = new PHPExecutor;
            }
        }
        return $this->executor;
    }
}

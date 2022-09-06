<?php

namespace Coffeemaru\Shellos;

use Coffeemaru\Shellos\Executors\Exec;

class ShellCommand
{
    private int $result;
    private array $output;
    private string $command;
    private SystemExecutor $exec;

    private static string $default_executor_class;

    public function __construct(string $command)
    {
        $this->result = 0;
        $this->output = [];
        $this->command = $command;
    }

    public function execute(): bool
    {
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

    public function setExecuter(SystemExecutor $exec): void
    {
        $this->exec = $exec;
    }

    public static function setDefaultExecutor(string $executor_class): void
    {
        static::$default_executor_class = $executor_class;
    }

    public function getExecutor(): SystemExecutor
    {
        if (!isset($this->exec)) {
            if (isset(static::$default_executor_class)) {
                $this->exec = new static::$default_executor_class();
            } else {
                $this->exec = new Exec();
            }
        }
        return $this->exec;
    }
}

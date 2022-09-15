<?php

namespace Coffeemaru\Shellos;

use Coffeemaru\Shellos\Executors\PHPExecutor;

class ShellCommand
{
    private int $result;
    private array $output;
    private string $command;
    private array $options;

    protected SystemExecutor $executor;
    protected static SystemExecutor $default_executor;

    public function __construct(string $command)
    {
        $this->options = [];
        $this->command = $command;
    }

    public function execute(): bool
    {
        $this->output = [];
        $executor = $this->getExecutor();
        $this->result = $executor->execute($this->command, $this->output, $this->options);
        return $this->result === 0;
    }

    public function wd(string $path): static
    {
        $this->options["wd"] = $path;
        return $this;
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

    public function __toString()
    {
        return $this->command;
    }

    public function getResultCode(): int
    {
        return $this->result;
    }
}

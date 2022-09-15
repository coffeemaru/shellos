<?php

namespace Coffeemaru\Shellos\Executors;

use Coffeemaru\Shellos\SystemExecutor;

/**
 * Executer that use the php exec function to run the system commands.
 */
class PHPExecutor implements SystemExecutor
{
    /**
     * @inheritdoc
     */
    public function execute(string $command, array &$output_lines = [], array $options = []): int
    {
        $result = 0;
        if (isset($options["wd"])) {
            $this->inDir($options["wd"], function () use ($command, &$output_lines, &$result) {
                exec($command . " 2>&1", $output_lines, $result);
            });
        } else {
            exec($command . " 2>&1", $output_lines, $result);
        }
        return $result;
    }

    protected function inDir(string $path, callable $callback)
    {
        $prev = getcwd();
        chdir($path);
        try {
            $callback();
        } finally {
            chdir($prev);
        }
    }
}

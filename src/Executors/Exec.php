<?php

namespace Coffeemaru\Shellos\Executors;

use Coffeemaru\Shellos\SystemExecutor;

/**
 * Executer that use the php exec function to run the system commands.
 */
class Exec implements SystemExecutor
{
    /**
     * @inheritdoc
     */
    public function execute(string $command, array &$output_lines = []): int
    {
        $result = 0;
        exec($command . " 2>&1", $output_lines, $result);
        return $result;
    }
}

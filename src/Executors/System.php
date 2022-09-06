<?php

namespace Coffeemaru\Shellos\Executors;

use Coffeemaru\Shellos\SystemExecutor;

class System implements SystemExecutor
{
    public function execute(string $command, array &$output_lines = []): int
    {
        $result = 0;
        system($command, $result);
        return $result;
    }
}

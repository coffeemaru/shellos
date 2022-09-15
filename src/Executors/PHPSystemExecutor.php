<?php

namespace Coffeemaru\Shellos\Executors;

class PHPSystemExecutor extends PHPExecutor
{
    public function execute(string $command, array &$output_lines = [], array $options = []): int
    {
        $result = 0;
        if (isset($options["wd"])) {
            $this->inDir($options["wd"], function () use ($command, &$result) {
                system($command, $result);
            });
        } else {
            system($command, $result);
        }
        return $result;
    }
}

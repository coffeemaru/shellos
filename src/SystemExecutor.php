<?php

namespace Coffeemaru\Shellos;

interface SystemExecutor
{
    /**
     * Execute the given command on the system.
     * @param string $command The command to be executed
     * @param array $output Optional output aray that can be used for the
     * executer to store the string output of the executed command 
     * @param array $options array with execution options 
     * @return int the result status code of the command execution
     */
    public function execute(
        string $command,
        array &$output_lines = [],
        array $options = [],
    ): int;
}

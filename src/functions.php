<?php

use Coffeemaru\Shellos\ShellCommand;

if (!function_exists("shell")) {
    /**
     * Shell create a new ShellCommand instance.
     *
     * @param $command The command to be executed, this command isn't
     * filtered on any form, use use escapeshellarg() or escapeshellcmd()
     * o ensure that users cannot trick the system into executing 
     * arbitrary commands. DON'T PASS USER INPUT DIRECTLY TO THIS FUNCTION
     */
    function shell(string $command): ShellCommand
    {
        return new ShellCommand($command);
    }
}

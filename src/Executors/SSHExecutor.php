<?php

namespace Coffeemaru\Shellos\Executors;

use Coffeemaru\Shellos\SystemExecutor;
use phpseclib3\Net\SSH2;

class SSHExecutor implements SystemExecutor
{
    private int $port;
    private string $host;
    private string $username;
    private string $password;

    public function __construct(string $host, int $port = 22, string $username, string $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }

    public function execute(string $command, array &$output_lines = []): int
    {
        $ssh = new SSH2($this->host, $this->port);
        if (!$ssh->login($this->username, $this->password)) {
            $output_lines[] = "was unable to login to the server";
            $output_lines[] = $ssh->getLastError();
            return -1;
        }
        $result = $ssh->exec($command);
        if ($result) {
            $output_lines = explode("\n", $result);
        }

        $status = $ssh->getExitStatus();
        return $status !== false ? $status : -1;
    }
}

<?php

namespace Tests;

use Coffeemaru\Shellos\Executors\SSHExecutor;
use Coffeemaru\Shellos\ShellCommand;
use phpseclib3\Net\SSH2;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Coffeemaru\Shellos\ShellCommand
 * @covers \Coffeemaru\Shellos\Executors\SSHExecutor
 */
class SSHExecutionTest extends TestCase
{
    private static SSH2 $ssh;
    private string $username;
    private string $password;
    private string $test_host;
    private int $test_port;

    protected function setUp(): void
    {
        parent::setUp();
        $this->username = getenv("TEST_SSH_USERNAME");
        $this->password = getenv("TEST_SSH_PASSWORD");
        $this->test_host = getenv("TEST_SSH_SERVER_HOST");
        $this->test_port = intval(getenv("TEST_SSH_SERVER_PORT"));
    }

    public function test_work_directory_change(): void
    {
        $ssh = $this->getSSHConnection();
        $ssh->exec('mkdir -p shellos/tests');
        $this->assertEquals(
            0,
            $ssh->getExitStatus(),
            'unable to create the test dir: '
        );

        $executer = new SSHExecutor(
            $this->test_host,
            $this->test_port,
            $this->username,
            $this->password
        );

        $output = [];
        $result = $executer->execute('ls', $output, ['wd' => 'shellos']);
        $this->assertEquals(
            0,
            $result,
            "no errors was expected on command execution"
        );
        $this->assertContains('tests', $output);
    }

    public function test_ssh_fail_call(): void
    {
        $command = new ShellCommand("ls --invalid-flag");
        $executor = new SSHExecutor($this->test_host, $this->test_port, $this->username, $this->password);
        $command->setExecutor($executor);
        $this->assertFalse($command->execute(), "with expect that the execution command return false");
    }

    public function test_ssh_server_connection(): void
    {
        $ssh = $this->getSSHConnection();
        $result = $ssh->exec("ls -a");
        $this->assertNotEmpty($result, "the test command don't return any content");
    }

    public function test_ssh_function(): void
    {
        $command = new ShellCommand("ls -a");
        $executor = new SSHExecutor($this->test_host, $this->test_port, $this->username, $this->password);
        $command->setExecutor($executor);
        $this->assertTrue($command->execute(), "ssh command execution fail");

        $ssh = $this->getSSHConnection();
        $expected = $ssh->exec("ls -a");
        $this->assertEquals(
            $expected,
            $command->getOutputString(),
            "the returned ssh command result is different from the expected"
        );
    }

    public function test_execution_with_invalid_login_credentials(): void
    {
        $command = new ShellCommand('pwd');
        $command->setExecutor(new SSHExecutor($this->test_host, $this->test_port, '', ''));
        $this->assertFalse($command->execute(), "was expected that the command fail");
        $this->assertEquals(
            -1,
            $command->getResultCode(),
            "was expected that the result code of the output was -1"
        );
        $this->assertContains(
            "was unable to login to the server",
            $command->getOutputLines(),
            "was expected that the login error message was added to the response lines"
        );
    }


    private function getSSHConnection(): SSH2
    {
        if (!isset(static::$ssh)) {
            $ssh = new SSH2($this->test_host, $this->test_port);
            if (!$ssh->login($this->username, $this->password)) {
                $this->fail("was unable to make the connection with the test server");
            }
            static::$ssh = $ssh;
        }
        return static::$ssh;
    }
}

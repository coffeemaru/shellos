<?php

namespace Tests;

use Coffeemaru\Shellos\Executors\Exec;
use Coffeemaru\Shellos\Executors\PHPExecutor;
use Coffeemaru\Shellos\Executors\PHPSystemExecutor;
use Coffeemaru\Shellos\ShellCommand;
use Coffeemaru\Shellos\SystemExecutor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ShellCommandTest extends TestCase
{
    private static string $tmpdir;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::$tmpdir = Utils::createTempDir();
    }

    public static function tearDownAfterClass(): void
    {
        rmdir(static::$tmpdir);
        parent::tearDownAfterClass();
    }

    /**
     * @covers ::shell
     * @covers \Coffeemaru\Shellos\ShellCommand
     */
    public function test_simple_shell_command_function(): void
    {
        $this->assertTrue(function_exists("shell"));
        $command = shell("pwd");
        $this->assertInstanceOf(ShellCommand::class, $command);

        /** @var MockObject */
        $executerMock = $this->createMock(SystemExecutor::class);
        $executerMock->expects($this->once())->method("execute")
            ->with("pwd")->willReturnCallback(function ($command, &$output = []) {
                $output[] = static::$tmpdir;
                return 0;
            });

        /** @var SystemExecutor $executerMock */
        $command->setExecutor($executerMock);

        $this->assertTrue($command->execute(), "was unable to execut a simple command");
        $this->assertEquals(
            static::$tmpdir,
            $command->getOutputString(),
            "the command output string isn't the expected temporary directory"
        );

        $this->assertEquals([static::$tmpdir], $command->getOutputLines());
    }

    /**
     * @covers \Coffeemaru\Shellos\ShellCommand
     */
    public function test_default_executor_setting(): void
    {
        $command = new ShellCommand("");
        $this->assertInstanceOf(
            PHPExecutor::class,
            $command->getExecutor(),
            "The default executor class must be the " . Exec::class
        );

        ShellCommand::setDefaultExecutor(new PHPSystemExecutor());
        $command = new ShellCommand("");
        $this->assertInstanceOf(
            PHPSystemExecutor::class,
            $command->getExecutor(),
            "the executor for new command must use the default executor class."
        );
    }
}

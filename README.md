# Shellos

An easy to use sdk to work with system commands calls.

## Simple command call

To create any command we will use the `shell` function, this function create a instance that can be
used to execute the command.

```php
$command = shell("ls")
if($command->execute()) {
    echo $command->getOutputString();
}
```

The command isn't executed on the shell call, to execute the command we need to use the `execute` 
method. This method return true if the command result is a success code. The method `getOutputString`
can be used to get all the output returned by the command, if we want each line of the command is 
possible use the `getOutputLines` method that returns an array with each line of the output.

```php
$command = shell("ls")
if($command->execute()) {
    for($command->getOutputLines() as $line){
        echo $line;
    }
}
```

## SSH connection.

Shellos support SSH remote execution via the SSHExecutor, if the SSHExecutor is configured as
default executor the functions will send the commands over a SSH tunnel instead of being executed 
locally.

```php
# first we need to inicialice the client with the server credentials.
$exec = new SSHExecutor($host, $port, $username, $password)
ShellCommand::setDefaultExecutor($exec);

# below this code all the `shell` functions calls will raise a ssh command on the remote host.
$command = ssh("ls -la")
if($command->execute()) { # <-- raise a ssh command 
}
```


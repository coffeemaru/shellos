# Shellos

An easy to use sdk to work with system commands calls.

## Simple command call

To create any command we will use the `shell` function, this function
create a command instance that can be used to execute the command.

```php
$command = shell("ls")
if($command->execute()) {
    echo $command->getOutputString();
}
```

The command isn't executed on the shell call, to execute the command we
need to use the `execute` method. This method return true if the command
return a success code. The method `getOutputString` return all the output
returned by the command, if we want each line of the command is possible
use the `getOutputLines` method that return an array with each line of 
the output.

```php
$command = shell("ls")
if($command->execute()) {
    for($command->getOutputLines() as $line){
        echo $line;
    }
}
```

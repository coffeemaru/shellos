<?php

require_once __DIR__ . "/../vendor/autoload.php";

$envfile = __DIR__ . "/../.env";
if (file_exists($envfile)) {
    $env = parse_ini_file($envfile);
    foreach ($env as $key => $value) {
        putenv("$key=$value");
    }
}

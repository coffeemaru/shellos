<?php

namespace Tests;

class Utils
{
    /**
     * Create new directory on the temporary system dir, the directory
     * will remain until the os delete it if isn't deleted manually.
     */
    public static function createTempDir(): string
    {
        $tdir = sys_get_temp_dir();
        $tmpname = tempnam($tdir, "shellos-test-");
        unlink($tmpname);
        mkdir($tmpname);
        return $tmpname;
    }
}

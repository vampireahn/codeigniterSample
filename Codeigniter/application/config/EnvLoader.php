<?php
//defined('BASEPATH') or define('BASEPATH', __DIR__);
if (!function_exists('ci3_load_env')) {
    function ci3_load_env($path) {
        if (!is_file($path)) return;
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(ltrim($line), '#') === 0) continue;
            if (strpos($line, '=') === false) continue;
            list($k,$v) = array_map('trim', explode('=', $line, 2));
            $v = trim($v, "\"'");
            $_ENV[$k] = $v;
            putenv("$k=$v");
        }
    }
}

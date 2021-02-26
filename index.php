<?php

if (version_compare(phpversion(), '5.3.0', '<')===true) {
    echo
    '<div style="font:12px/1.35em arial, helvetica, sans-serif;">
        <div style="margin:0 0 25px 0; border-bottom:1px solid #ccc;">
            <h3 style="margin:0; font-size:1.7em; font-weight:normal; text-transform:none; text-align:left; color:#2f2f2f;">
                Whoops, it looks like you have an invalid PHP version.
            </h3>
        </div>
        <p>Vbox supports PHP 5.3.0 or newer.</p>
    </div>';
    exit;
}

/**
 * Compilation includes configuration file
 */
define('VT_ROOT', getcwd());

$vtFilename = VT_ROOT . '/app/VT.php';

require VT_ROOT . '/app/bootstrap.php';
require_once $vtFilename;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

umask(0);

Virtual::run();

require VT_ROOT . '/app/test.php';
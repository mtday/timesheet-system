<?php

// Identify the root of the system.
$root = dirname(__FILE__) . "/zendapp";

// Set the include path.
set_include_path(
    $root . '/application'                                  . PATH_SEPARATOR .
    $root . '/application/daos'                             . PATH_SEPARATOR .
    $root . '/application/util'                             . PATH_SEPARATOR .
    $root . '/application/modules/admin/controllers'        . PATH_SEPARATOR .
    $root . '/application/modules/admin/forms'              . PATH_SEPARATOR .
    $root . '/application/modules/admin/views/helpers'      . PATH_SEPARATOR .
    $root . '/application/modules/payroll/controllers'      . PATH_SEPARATOR .
    $root . '/application/modules/payroll/views/helpers'    . PATH_SEPARATOR .
    $root . '/application/modules/manager/controllers'      . PATH_SEPARATOR .
    $root . '/application/modules/manager/views/helpers'    . PATH_SEPARATOR .
    $root . '/application/modules/supervisor/controllers'   . PATH_SEPARATOR .
    $root . '/application/modules/supervisor/views/helpers' . PATH_SEPARATOR .
    $root . '/application/modules/user/controllers'         . PATH_SEPARATOR .
    $root . '/application/modules/user/views/helpers'       . PATH_SEPARATOR .
    $root . '/application/modules/default/controllers'      . PATH_SEPARATOR .
    $root . '/application/modules/default/forms'            . PATH_SEPARATOR .
    $root . '/application/modules/default/views/helpers'    . PATH_SEPARATOR .
    $root . '/library'                                      . PATH_SEPARATOR .
    get_include_path()
);

// Perform all system initialization via Bootstrap.
require_once 'Bootstrap.php';
Bootstrap::run();


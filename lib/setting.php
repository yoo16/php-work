<?php
/**
 * @author  Yohei Yoshikawa
 *
 * Copyright (c) 2013 Yohei Yoshikawa (http://yoo-s.com/)
 */

if (!defined('BASE_DIR')) define('BASE_DIR', dirname(dirname(__FILE__)) . '/');

ini_set('display_errors', 'Off');
ini_set('log_errors', 'On');
ini_set('mbstring.language', 'Japanese');
ini_set('mbstring.internal_encoding', 'UTF-8');
ini_set('default_charset', 'UTF-8');

set_include_path(BASE_DIR.'app'.PATH_SEPARATOR.BASE_DIR.'lib');

function hostname() {
    static $hostname;
    $hostname = strtolower(exec('hostname'));
    return $hostname;
}

if (!@include_once(BASE_DIR.'app/setting.php')) {
    if (!@include_once(BASE_DIR.'app/settings/'.hostname().'.php')) {
        if (!@include_once(BASE_DIR.'app/settings/default.php')) {
            error_log('cannot find setting');
        }
    }
}
@include_once BASE_DIR.'app/application.php';
?>

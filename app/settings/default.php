<?php
define('PROJECT_NAME', 'php-work');
define('DEFAULT_LOCALE', 'ja');
define('LOG_DIR', BASE_DIR.'log/');

ini_set('error_log', LOG_DIR.date('Ymd').'.log');

ini_set('display_errors', false);
ini_set('log_errors', true);
error_reporting(E_ALL & ~E_NOTICE);

define('PROJECT_DIR', BASE_DIR);
define('TMP_DIR', BASE_DIR . 'tmp/');
define('DB_DIR', BASE_DIR.'db/');

define('DEBUG', true);

define('MAIL_SETTING_FILE', DB_DIR.'mail/default.csv');

define('DB_SETTING_FILE', BASE_DIR.'app/settings/db.php');
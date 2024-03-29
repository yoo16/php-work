<?php
define('PROJECT_NAME', 'php-work');
define('LOG_DIR', BASE_DIR.'log/');

ini_set('error_log', LOG_DIR.date('Ymd').'.log');
ini_set('display_errors', false);
ini_set('log_errors', true);
error_reporting(E_ALL & ~E_NOTICE);

define('PROJECT_DIR', BASE_DIR);
define('TMP_DIR', BASE_DIR . 'tmp/');
define('DB_DIR', BASE_DIR.'db/');

define('DEBUG', true);
define('SQL_LOG', true);

define('MAIL_SETTING_FILE', DB_DIR.'mail/default.csv');

//shell
define('COMAND_PHP_PATH', '/usr/bin/php');

//Database Host
define('DB_HOSTS_CSV', DB_DIR.'db_info/db_hosts.csv');
define('DB_USERS_CSV', DB_DIR.'db_info/db_users.csv');
define('DB_PORTS_CSV', DB_DIR.'db_info/db_ports.csv');

//PostgreSQL
define('DB_SETTING_FILE', BASE_DIR.'app/settings/pgsql/default.php');

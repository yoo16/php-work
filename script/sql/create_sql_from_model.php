<?php
/**
 * Copyright (c) 2017 Yohei Yoshikawa (https://github.com/yoo16/)
 *
 */
require_once dirname(__FILE__) . '/../../lib/Controller.php';

echo('-- Create SQL --'.PHP_EOL);
$pgsql = new PgsqlEntity();
$sql = $pgsql->createTablesSQLForProject();

if (file_exists(DB_DIR)) {
    $sql_path = DB_DIR.'sql/create.sql';
    file_put_contents($sql_path, $sql);
}

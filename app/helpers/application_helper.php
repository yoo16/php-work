<?php
/**
 * helpersファイル読み込み
 *
 * @author  Yohei Yoshikawa 
 * @create  2010/02/06 
 */

require_once 'lib/_application_helper.php';
require_once 'localize/jp.php';

require_once 'message_helper.php';

/**
 * ラベル有効
 *
 * @param Boolean $value
 * @return String
 */
function labelActive($value) {
    return ($value) ? '○' : '×';
}
?>

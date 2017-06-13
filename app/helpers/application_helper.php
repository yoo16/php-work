<?php
/**
 * helpersファイル読み込み
 *
 * @author  Yohei Yoshikawa 
 * @create  2010/02/06 
 */

require_once 'lib/_application_helper.php';
require_once 'message_helper.php';

function undefineLabel($key, $value) {
      if (!defined($key)) {
          $tag = undefineLabelTag($key, $value);
      }
      if (defined($key)) {
          $tag.= $value;
      }
      return $tag;
}

function undefineLabelTag($key, $value) {
      $tag = '<label class="badge badge-danger">Undefined</label>';
      return $tag;
}
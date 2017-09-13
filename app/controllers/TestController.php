<?php
/**
 * RegistController 
 *
 * @access  public
 * @author  Yohei Yoshikawa
 */

require_once 'AppController.php';
 
class TestController extends AppController {

    var $name = 'test';
    var $layout = 'test';

   /**
    * 事前処理
    *
    * @access public
    * @param String $action
    * @return void
    */ 
    function before_action($action) {
        parent::before_action($action);
    }

    function action_query() {
        
    }

}
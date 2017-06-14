<?php
/**
 * User
 *
 * @package 
 * @author  Yohei Yoshikawa
 * @create  2013-04-15 16:33:13
 */

require_once 'vo/_User.php';

class User extends _User {

    function __construct($params=null) {
        parent::__construct($params);        
    }

   /**
    * validate
    *
    * @param 
    * @return void
    */ 
    function validate() {
        parent::validate();
    }

}
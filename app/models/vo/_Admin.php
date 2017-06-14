<?php
/**
 * _Admin 
 *
 * @author   
 * @create  2013-04-15 16:33:13
 */

require_once 'PgsqlEntity.php';

class _Admin extends PgsqlEntity {
    
    var $name = 'admins';
    static $entity_name = 'admin';

    var $columns = array(
        'created_at' => array('type' => 't'),
        'updated_at' => array('type' => 't'),
        'sort_order' => array('type' => 'i'),
        'login_name' => array('type' => 's', 'required' => true),
        'email' => array('type' => 's'),
        'last_name' => array('type' => 's'),
        'first_name' => array('type' => 's'),
        'password' => array('type' => 's'),
        'tmp_password' => array('type' => 's'),
        'tmp_password' => array('type' => 's'),
        'memo' => array('type' => 's'),
    );

    function __construct($params=null) {
        parent::__construct();        
        if ($params['pg_info']) $this->pg_info = $params['pg_info'];
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
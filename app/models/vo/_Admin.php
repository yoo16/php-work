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
    var $entity_name = 'admin';

    var $columns = array(
        'created_at' => array('type' => 'timestamp'),
        'updated_at' => array('type' => 'timestamp'),
        'sort_order' => array('type' => 'int4'),
        'login_name' => array('type' => 'varchar', 'length' => 256, 'required' => true),
        'email' => array('type' => 'varchar', 'length' => 256),
        'last_name' => array('type' => 'varchar', 'length' => 256),
        'first_name' => array('type' => 'varchar', 'length' => 256),
        'password' => array('type' => 'varchar', 'length' => 256),
        'tmp_password' => array('type' => 'varchar', 'length' => 256),
        'memo' => array('type' => 'text'),
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
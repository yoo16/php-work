<?php
/**
 * _User 
 *
 * @author   
 * @create  2013-04-15 16:33:13
 */

require_once 'PgsqlEntity.php';

class _User extends PgsqlEntity {
    
    var $name = 'users';
    var $entity_name = 'user';

    var $columns = array(
        'created_at' => array('type' => 'timestamp'),
        'updated_at' => array('type' => 'timestamp'),
        'sort_order' => array('type' => 'int4'),
        'last_name_kana' => array('type' => 'varchar', 'length' => 256),
        'first_name_kana' => array('type' => 'varchar', 'length' => 256),
        'last_name' => array('type' => 'varchar', 'length' => 256, 'required' => true),
        'first_name' => array('type' => 'varchar', 'length' => 256, 'required' => true),
        'password' => array('type' => 'varchar', 'length' => 256),
        'tmp_password' => array('type' => 'varchar', 'length' => 256),
        'email' => array('type' => 'varchar', 'length' => 256, 'required' => true),
        'tel' => array('type' => 'varchar', 'length' => 256),
        'tmp_password' => array('type' => 'varchar', 'length' => 256),
        'birthday_at' => array('type' => 'timestamp'),
        'gender' => array('type' => 'varchar', 'length' => 256),
        'memo' => array('type' => 'varchar', 'length' => 256),
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
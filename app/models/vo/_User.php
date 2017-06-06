<?php
/**
 * _User 
 *
 * @author   
 * @create  2013-04-15 16:33:13
 *
 */

require_once 'PgsqlEntity.php';

class _User extends PgsqlEntity {
    
    var $name = 'users';
    static $entity_name = 'user';

    var $columns = array(
        'created_at' => array('type' => 't'),
        'updated_at' => array('type' => 't'),
        'sort_order' => array('type' => 'i'),
        'last_name_kana' => array('type' => 's'),
        'first_name_kana' => array('type' => 's'),
        'last_name' => array('type' => 's', 'required' => true),
        'first_name' => array('type' => 's', 'required' => true),
        'password' => array('type' => 's'),
        'tmp_password' => array('type' => 's'),
        'email' => array('type' => 's', 'required' => true),
        'tel' => array('type' => 's'),
        'tmp_password' => array('type' => 's'),
        'birthday_at' => array('type' => 't'),
        'gender' => array('type' => 's'),
        'memo' => array('type' => 's'),
    );

    function __construct($params=null) {
        parent::__construct();        
        if ($params['pg_info']) $this->pg_info = $params['pg_info'];
    }

    // static function init($params=null) {
    //     $class_name = ucfirst(self::$entity_name);
    //     $instance = new $class_name();
    //     if ($params) {
    //         foreach ($params as $key => $value) {
    //             $instance->$key = $value;
    //         }
    //     }
    //     return $instance;
    // }

    // static function get($params=null) {
    //     $instance = self::init($params);
    //     $instance->select();
    //     return $instance;
    // }

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

?>
<?php
/**
 * User 
 * 
 * @create  2018-06-20 17:28:31 
 */

//namespace sample;

require_once 'PgsqlEntity.php';

class _User extends PgsqlEntity {

    public $id_column = 'id';
    public $name = 'users';
    public $entity_name = 'user';

    public $columns = array(
        'country_id' => array('type' => 'int4'),
        'created_at' => array('type' => 'timestamp'),
        'name' => array('type' => 'varchar', 'length' => 256, 'is_required' => true),
        'sort_order' => array('type' => 'int4'),
        'updated_at' => array('type' => 'timestamp'),
    );

    public $primary_key = 'users_pkey';
    public $foreign = array(
            'users_country_id_fkey' => [
                                  'column' => 'country_id',
                                  'class_name' => 'Country',
                                  'foreign_table' => 'countries',
                                  'foreign_column' => 'id',
                                  'cascade_update_type' => 'a',
                                  'cascade_delete_type' => 'a',
                                  ],
    );




    function __construct($params = null) {
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
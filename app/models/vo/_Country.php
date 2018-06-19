<?php
/**
 * Country 
 * 
 * @create  2018-06-19 16:09:34 
 */

//namespace sample;

require_once 'PgsqlEntity.php';

class _Country extends PgsqlEntity {

    public $id_column = 'id';
    public $name = 'countries';
    public $entity_name = 'country';

    public $columns = array(
        'created_at' => array('type' => 'timestamp'),
        'name' => array('type' => 'varchar', 'length' => 256, 'is_required' => true),
        'sort_order' => array('type' => 'int4'),
        'updated_at' => array('type' => 'timestamp'),
    );

    public $primary_key = 'Country_pkey';

    public $unique = array(
            'countries_name_key' => [
                        'name',
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
<?php
/**
 * Country 
 * 
 * @create  2019/12/14 11:18:38 
 */

require_once 'PwPgsql.php';

class _Country extends PwPgsql {

    public $id_column = 'id';
    public $name = 'reports';
    public $entity_name = 'report';

    public $columns = [
        'created_at' => ['type' => 'timestamp'],
        'name' => ['type' => 'varchar', 'length' => 64],
        'reported_at' => ['type' => 'timestamp'],
        'sort_order' => ['type' => 'int4'],
        'updated_at' => ['type' => 'timestamp'],
    ];

    public $primary_key = 'reports_pkey';
    public $foreign = [
            'reports_student_id_fkey' => [
                                  'column' => 'student_id',
                                  'class_name' => 'Student',
                                  'foreign_table' => 'students',
                                  'foreign_column' => 'id',
                                  'cascade_update_type' => 'a',
                                  'cascade_delete_type' => 'c',
                                  ],
    ];

    public $index_keys = [
    'reports_pkey' => 'CREATE UNIQUE INDEX reports_pkey ON public.reports USING btree (id)',
    ];


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
<?php
/**
 * User 
 * 
 * @create  2019/12/14 10:09:58 
 */

require_once 'PwPgsql.php';

class _User extends PwPgsql {

    public $id_column = 'id';
    public $name = 'students';
    public $entity_name = 'student';

    public $columns = [
        'birthday_at' => ['type' => 'timestamp'],
        'code' => ['type' => 'varchar', 'length' => 64],
        'created_at' => ['type' => 'timestamp'],
        'first_name' => ['type' => 'varchar', 'length' => 64],
        'first_name_kana' => ['type' => 'varchar', 'length' => 64],
        'last_name' => ['type' => 'varchar', 'length' => 64],
        'last_name_kana' => ['type' => 'varchar', 'length' => 64],
        'sort_order' => ['type' => 'int4'],
        'updated_at' => ['type' => 'timestamp'],
    ];

    public $primary_key = 'students_pkey';

    public $unique = [
            'students_code_key' => [
                        'code',
                        ],
    ];
    public $index_keys = [
    'students_pkey' => 'CREATE UNIQUE INDEX students_pkey ON public.students USING btree (id)',
    'students_code_key' => 'CREATE UNIQUE INDEX students_code_key ON public.students USING btree (code)',
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
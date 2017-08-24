<?php
/**
 * RootController 
 *
 * @author  Yohei Yoshikawa
 * @create  2012/10/03 
 */
require_once 'AppController.php';

class RootController extends AppController {

    function index() {
        //$pgsql_entity = new PgsqlEntity();
        //$this->pg_connection = $pgsql_entity->connection();
        $this->hostname = PwSetting::hostname();
        $this->debug_traces = debug_backtrace(true);
    }

    function defined() {
        $defined_constants = get_defined_constants(true);
        $this->defined_constants = $defined_constants['user'];
    }

}
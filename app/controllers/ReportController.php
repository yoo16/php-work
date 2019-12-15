<?php
/**
 * ReportController 
 *
 * @create  2019/12/14 11:30:55 
 */

require_once 'AppController.php';

class ReportController extends AppController {

    public $name = 'report';

   /**
    * before_action
    *
    * @param string $action
    * @return void
    */
    function before_action($action) {
        parent::before_action($action);
        
    }

   /**
    * index
    *
    * @param
    * @return void
    */
    function index() {
        PwSession::clear('posts');
        $this->redirectTo(['action' => 'list']);;
    }

   /**
    * list
    *
    * @param
    * @return void
    */
    function action_list() {
        $this->report = DB::model('Report')->all();

                
    }

   /**
    * new
    *
    * @param
    * @return void
    */
    function action_new() {
        $this->report = DB::model('Report')->newPage();
    }

   /**
    * edit
    *
    * @param
    * @return void
    */
    function action_edit() {
        $this->report = DB::model('Report')->editPage();
    }

   /**
    * add
    *
    * @param
    * @return void
    */
    function action_add() {
        $this->redirectForAdd($this->insertByModel('Report'));
    }

   /**
    * update
    *
    * @param
    * @return void
    */
    function action_update() {
        $this->redirectForUpdate($this->updateByModel('Report'));
    }

   /**
    * delete
    *
    * @param
    * @return void
    */
    function action_delete() {
        $this->redirectForDelete($this->deleteByModel('Report'));
    }

   /**
    * update sort order
    *
    * @param
    * @return void
    */
    function action_update_sort() {
        $this->updateSort('Report');
    }

}
<?php
/**
 * StudentController 
 *
 * @create  2019/12/14 11:30:55 
 */

require_once 'AppController.php';

class StudentController extends AppController {

    public $name = 'student';

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
        $this->student = DB::model('Student')->all();

                
    }

   /**
    * new
    *
    * @param
    * @return void
    */
    function action_new() {
        $this->student = DB::model('Student')->newPage();
    }

   /**
    * edit
    *
    * @param
    * @return void
    */
    function action_edit() {
        $this->student = DB::model('Student')->editPage();
    }

   /**
    * add
    *
    * @param
    * @return void
    */
    function action_add() {
        $this->redirectForAdd($this->insertByModel('Student'));
    }

   /**
    * update
    *
    * @param
    * @return void
    */
    function action_update() {
        $this->redirectForUpdate($this->updateByModel('Student'));
    }

   /**
    * delete
    *
    * @param
    * @return void
    */
    function action_delete() {
        $this->redirectForDelete($this->deleteByModel('Student'));
    }

   /**
    * update sort order
    *
    * @param
    * @return void
    */
    function action_update_sort() {
        $this->updateSort('Student');
    }

}
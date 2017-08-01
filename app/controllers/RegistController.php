<?php
/**
 * RegistController 
 *
 * @access  public
 * @author  Yohei Yoshikawa
 */

require_once 'AppController.php';
 
class RegistController extends AppController {

    var $name = 'regist';
    var $layout = 'user';

   /**
    * 事前処理
    *
    * @access public
    * @param String $action
    * @return void
    */ 
    function before_action($action) {
        parent::before_action($action);
    }

   /**
    * トップページ
    * セッションクリア＆一覧画面リダイレクト
    *
    * @access public
    * @param
    * @return void
    */ 
    function index() {
        unset($this->session['posts']);
        $this->redirect_to('new');
    }

    function action_new() {
        $this->user = DB::table('User')->takeValues($this->session['posts']);
        $this->errors = $this->flash['errors'];
    }

   /**
    * 登録
    *
    * @access public
    * @param
    * @return void
    */ 
    function add() {
        if (!isPost()) exit;

        $posts = $this->session['posts'] = $_POST;
        $posts['password'] = hash('sha256', $posts['password'], false);

        $user = DB::table('User')->where("email = '{$posts['email']}'")->selectOne();
        if ($user->value['id']) {
            $user->addError('email', 'exists');
            $this->flash['errors'] = $user->errors;
            $this->redirect_to('index');
            exit;
        }
        $user = DB::table('User')->insert($posts);

        if ($user->errors) {
            $this->flash['errors'] = $user->errors;
            $this->redirect_to('new');
        } else {
            $this->redirect_to('user/login');
        }
    }

}
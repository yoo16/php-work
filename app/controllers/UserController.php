<?php
/**
 * UserController 
 *
 * @author  Yohei Yoshikawa
 * @create  2012/10/03 
 */
require_once 'AppController.php';
 
class UserController extends AppController {

    var $name = 'user';
    var $layout = 'user';
    var $escape_auth_actions = array('auth', 'login', 'logout', 'regist', 'add');
    var $current_main_menu = 'user';

   /**
    * before_action
    *
    * @param string $action
    * @return void
    */ 
    function before_action($action) {
        parent::before_action($action);
        $this->checkLogin($action);
    }

   /**
    * checkLogin
    *
    * @param string $action
    * @return void
    */ 
    private function checkLogin($action) {
        if (!in_array($action, $this->escape_auth_actions)) {
            $this->user = AppSession::get('user');
            if (!$this->user['id']) {
                $this->redirect_to('user/login');
                return;
            }
        }
    }

   /**
    * cancel
    *
    * @param
    * @return void
    */ 
    function action_cancel() {
        unset($this->session['posts']);
        $this->redirect_to('index');
    }

   /**
    * action_clear_search
    *
    * @param
    * @return void
    */ 
    function action_clear_search() {
        $this->redirect_to('list');
    }

   /**
    * action_search
    *
    * 検索
    *
    * @access public
    * @param
    * @return void
    */ 
    function action_search() {
        $this->redirect_to('list');
    }

   /**
    * トップページ
    * セッションクリア＆一覧画面リダイレクト
    *
    * @param
    * @return void
    */ 
    function index() {
        unset($this->session['posts']);
    }

   /**
    * 認証
    *
    * @param
    * @return void
    */ 
    function action_auth() {
        if (!isPost()) exit;

        $posts = $_POST['user'];
        $posts['password'] = hash('sha256', $posts['password'], false);

        $user = DB::table('User')
                            ->where("email = '{$posts['email']}'")
                            ->where("password = '{$posts['password']}'")
                            ->selectOne();

        if ($user->value['id'] > 0) {
            AppSession::set('user', $user->value);
            $this->redirect_to('index');
            exit;
        } else {
            $this->redirect_to('login');
            exit;
        }
        $this->redirect_to('login');
    }

   /**
    * edit
    *
    * @param
    * @return void
    */ 
    function action_edit() {
        $this->user = DB::table('User')->fetch($this->user['id']);
    }

   /**
    * edit
    *
    * @param
    * @return void
    */ 
    function action_update() {
        if (!isPost()) exit;
        $this->session['posts'] = $_POST['user'];
        $user = DB::table('User')->update($this->session['posts'], $this->user['id']);
        $this->redirect_to('edit');
    }

   /**
    * ログイン
    *
    * @param
    * @return void
    */ 
    function action_login() {
        $this->user = new User();
    }

   /**
    * action_logout
    *
    * @param
    * @return void
    */ 
    function action_logout() {
        AppSession::clear('user');
        $this->redirect_to('user/login');
    }

   /**
    * regist
    *
    * @param
    * @return void
    */ 
    function regist() {
        $this->user = AppSession::get('posts');
        $this->errors = $this->flash['errors'];
    }

}
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
    * 事前処理
    *
    * @access public
    * @param String $action
    * @return void
    */ 
    function before_action($action) {
        parent::before_action($action);
        $this->checkLogin($action);
    }

   /**
    * ログインチェック
    *
    * @access private
    * @param String $action
    * @return void
    */ 
    private function checkLogin($action) {
        if (!in_array($action, $this->escape_auth_actions)) {
            $this->user = AppSession::getUserSession('user');
            if (!$this->user['id']) {
                $this->redirect_to('user/login');
                return;
            }
        }
    }

   /**
    * action_cancel
    *
    * キャンセル
    * セッションクリア＆トップページ
    *
    * @access public
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
    * 検索クリア
    *
    * @access public
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
    * 自動ログイン
    *
    * @access public
    * @param
    * @return void
    **/
    function action_auto_login() {
        // $user = new User();
        // $user->find($_REQUEST['user_id']);
        // if ($user->value['id'] > 0) {
        //     AppSession::setUserSession($this->sid, 'user', $user);
        // }
        // $this->redirect_to('user/');
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
    }

   /**
    * 認証
    *
    * @access public
    * @param
    * @return void
    */ 
    function auth() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $posts = $_POST['user'];
            $posts['password'] = hash('sha256', $posts['password'], false);

            $conditions = null;
            $user = new User();
            $user->where("email = '{$posts['email']}'")
                ->where("password = '{$posts['password']}'")
                ->selectOne();

            if ($user->value['id'] > 0) {
                AppSession::setUserSession('user', $user->value);
                $this->default_page();
                exit;
            } else {
                $this->redirect_to('login');
                exit;
            }
        }
        $this->redirect_to('login');
    }

   /**
    * ログイン
    *
    * @access public
    * @param
    * @return void
    */ 
    function action_login() {
        AppSession::clearSession('posts');
    }

   /**
    * action_logout
    *
    * ログアウト
    *
    * @access public
    * @param
    * @return void
    */ 
    function action_logout() {
        AppSession::clearUserSession($this->sid, 'user');
        $this->redirect_to('user/login');
    }

   /**
    * 認証
    *
    * @access public
    * @param
    * @return void
    */ 
    function default_page() {
        $this->redirect_to('user/index');
    }


   /**
    * 登録
    *
    * @access public
    * @param
    * @return void
    */ 
    function regist() {
        $this->user = AppSession::getSession('posts');
        var_dump($this->flash['error']);
    }

   /**
    * 登録
    *
    * @access public
    * @param
    * @return void
    */ 
    function add() {
        $_POST['user']['password'] = hash('sha256', $_POST['user']['password'], false);

        AppSession::setSession('posts', $_POST['user']);

        $user = new User();
        $user->takeValues($_POST['user']);
        $user->validate();
        $user->save();

        if ($user->errors) {
            $this->flash['error'] = $user->errors;
            $this->redirect_to('regist');
        } else {
            $this->redirect_to('login');
        }
    }

}

?>
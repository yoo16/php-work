<?php
/**
 * AdminController 
 *
 * @author  Yohei Yoshikawa
 * @create  2012/10/03 
 */
require_once 'AppController.php';

class AdminController extends AppController {

    var $name = 'admin';
    var $layout = 'admin';
    var $escape_auth_actions = array('login', 'logout', 'auth');

    function before_action($action) {
        parent::before_action($action);
        $this->checkLogin($action);
    }

    function checkLogin($action) {
        if (!in_array($action, $this->escape_auth_actions)) {
            $this->admin = AppSession::getAdminSession('admin');
            if (!$this->admin['id']) {
                $this->redirect_to('admin/login');
                return;
            }
        }
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
     **/ 
    function auth() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $posts = $_POST['admin'];
            if (ADMIN_ID == $posts['login_name'] && ADMIN_PW == $posts['password']) {
                $admin['id'] = 1;
                AppSession::setAdminSession('admin', $admin);
            }
            $this->admin = AppSession::getAdminSession('admin');
            if ($this->admin['id'] > 0) {
                $this->default_page();
                exit;
            }
        }
        $this->redirect_to('login');
    }

   /**
    * login
    *
    * @param
    * @return void
    */ 
    function action_login() {
    }

   /**
    * logout
    *
    * @param
    * @return void
    */ 
    function action_logout() {
        AppSession::clearAdminSession($this->sid, 'admin');
        $this->redirect_to('admin/login');
    }

   /**
    * default_page
    *
    * @param
    * @return void
    */ 
    function default_page() {
        $this->redirect_to('admin/');
    }

    function log() {

    }

    function log_list() {
        $values = FileManager::logFiles();
        $values = json_encode($values);
        echo($values);
        exit;
    }

    function log_file() {
        $path = LOG_DIR.$_REQUEST['filename'].'.log';
        $values = file_get_contents($path);
        echo($values);
        exit;
    }

    function delete_log() {
        $path = LOG_DIR.$_REQUEST['filename'].'.log';
        FileManager::removeFile($path);

        $values['success'] = true;
        $values = json_encode($values);
        echo($values);
        exit;
    }

}

?>

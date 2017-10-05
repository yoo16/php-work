<?php
/**
 * AppController 
 *
 * @author  Yohei Yoshikawa
 * @create  2012/10/03 
 */
ApplicationLoader::autoloadModel();

require_once 'Controller.php';

class AppController extends Controller {
    var $title = HTML_TITLE;
    var $csv_options = array('gender', 'prefecture');
    var $layout = 'root';

    function before_action($action) {
        parent::before_action($action);

        $this->loadDefaultCsvOptions();
    }

    /**
     * reloadDefaultCsvOptions
     * 
     * @return void
     */
    function reloadDefaultCsvOptions() {
        AppSession::clearWithKey('app', 'csv_options');
        $this->loadDefaultCsvOptions();
    }

    /**
     * loadDefaultCsvOptions
     * 
     * @return void
     */
    function loadDefaultCsvOptions() {
        $this->csv_options = AppSession::getWithKey('app', 'csv_options');

        if ($this->csv_options) return;

        $path = DB_DIR."records/*.csv";
        foreach (glob($path) as $file_path) {
            $path_info = pathinfo($file_path);
            $this->csv_options[$path_info['filename']] = CsvLite::keyValues($file_path);
        }
        AppSession::setWithKey('app', 'csv_options', $this->csv_options);
    }

    function isRequestPost() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') exit;
    }

}
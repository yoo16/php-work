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
    var $csv_options;
    var $layout = 'root';

    function before_action($action) {
        parent::before_action($action);
    }

}
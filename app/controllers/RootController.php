<?php
require_once 'AppController.php';

class RootController extends AppController {
    var $static_pages = true;

    function index() {

    }

    function page1() {
        $values = null;
        $values[] = array('id' => 1, 'name' => 'Tokyo', 'country' => 'Japan');
        $values[] = array('id' => 2, 'name' => 'Osaka', 'country' => 'Japan');
        $values[] = array('id' => 3, 'name' => 'Nagoya', 'country' => 'Japan');
        $values[] = array('id' => 4, 'name' => 'NewYork', 'country' => 'USA');
        $values[] = array('id' => 5, 'name' => 'Paris', 'country' => 'France');

        $this->forms['area']['id'] = 'area';
        $this->forms['area']['class'] = 'area';
        $this->forms['area']['name'] = 'select[area]';
        $this->forms['area']['value_key'] = 'id';
        $this->forms['area']['label_key'] = array('country', 'name');
        $this->forms['area']['label_separate'] = ' : ';
        $this->forms['area']['values'] = $values;
        $this->forms['area']['unselect']['label'] = "-------";
        $this->forms['area']['unselect']['value'] = -1;
        $this->forms['area']['js']['event'] = 'onChange';
        $this->forms['area']['js']['function'] = "alert($('#area').val())";

        $this->forms['area_radio'] = $this->forms['area'];
        $this->forms['area_radio']['id'] = 'area_radio';
        $this->forms['area_radio']['name'] = 'radio[area]';
        $this->forms['area_radio']['js']['event'] = 'onChange';
        $this->forms['area_radio']['js']['function'] = "alert($(this).val())";
        unset($this->forms['area_radio']['unselect']);

        $this->forms['area_checkbox'] = $this->forms['area'];
        $this->forms['area_checkbox']['id'] = 'area_checkbox';
        $this->forms['area_checkbox']['name'] = 'checkbox[area]';
        unset($this->forms['area_checkbox']['js']);
        unset($this->forms['area_checkbox']['unselect']);

        //select_date
        $this->forms['date']['name'] = 'date';
        $this->forms['date']['selected']['year'] = date('Y');
        $this->forms['date']['selected']['month'] = date('m');
        $this->forms['date']['selected']['day'] = date('d');
        $this->forms['date']['years'] = range(date('Y') + 1,  date('Y') - 10);
        $this->forms['date']['class'] = 'input-small';
        $this->forms['date']['unselect']['value'] = '';
        $this->forms['date']['unselect']['label'] = '';

    	//select
    	$params = null;
    	$values = null;
    	$value['value'] = '1';
    	$value['label'] = 'Menu1';
    	$values[] = $value;

    	$value['value'] = '2';
    	$value['label'] = 'Menu2';
    	$values[] = $value;

    	$value['value'] = '3';
    	$value['label'] = 'Menu3';
    	$values[] = $value;

    	$this->forms['menu']['name'] = 'menu';
    	$this->forms['menu']['values'] = $values;
    	$this->forms['menu']['class'] = 'input-xlarge';
    	$this->forms['menu']['unselect'] = true;

    	//select
    	$params = null;
    	$values = null;
    	$value['value'] = 'male';
    	$value['label'] = 'Male';
    	$values[] = $value;

    	$value['value'] = 'female';
    	$value['label'] = 'Female';
    	$values[] = $value;

    	$this->forms['gender']['name'] = 'gender';
    	$this->forms['gender']['values'] = $values;

    	//checkbox
    	$params = null;
    	$values = null;
    	$params['name'] = 'active';
    	$params['value'] = 'active';
    	$params['label'] = 'Active';
    	$this->forms['active'] = $params;

    	//checkbox multi
    	$params = null;
    	$values = null;
    	$params['name'] = 'question';
    	$value['value'] = 'q1';
    	$value['label'] = 'Question1';
    	$params['values'][] = $value;

    	$value['value'] = 'q2';
    	$value['label'] = 'Question2';
    	$value['is_selected'] = true;
    	$params['values'][] = $value;

    	$value['value'] = 'q3';
    	$value['label'] = 'Question3';
    	$value['is_selected'] = true;
    	$params['values'][] = $value;

    	$this->forms['questions'] = $params;
    }

    function action_upload_file() {
        $this->contents = FileManager::loadContents();
        $this->encoding = mb_detect_encoding($this->contents);
        $this->contents = mb_convert_encoding($this->contents, 'UTF-8', $this->encoding);

        $this->upload_file_path = FileManager::uploadFilePath();

        $this->file_type = FileManager::uploadFileType();
    }

}

?>

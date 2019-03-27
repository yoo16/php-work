<?php
/**
 * SampleController 
 *
 * @author  Yohei Yoshikawa
 * @create  2012/10/03 
 */
require_once 'AppController.php';

class SampleController extends AppController {

    var $layout = 'sample';

    function index() {
		$this->country = DB::model('Country');
		$this->country->fetch(1);
	}
	
	/**
	 * controller
	 *
	 * @return void
	 */
	function action_controller_ex() {

	}
	
	/**
	 * model
	 *
	 * @return void
	 */
	function action_model_ex() {
		//$pgsql = new PwEntity();
		//$results = $pgsql->createDatabase();
		//var_export($results);

		//$country = DB::model('Country')->insert(['name' => 'Japan', 'area' => 'Asia', 'sort_order' => 1]);
		//$country = DB::model('Country')->fetch(2);
		//var_export($country->value);
		//$country = DB::model('Country')->update(['name' => 'United States America'], 2);
		//$country = DB::model('Country')->update(['area' => 'Asia'], 1);
		//$country = DB::model('Country')->delete(5);
		//$country = DB::model('Country')->deletes();
		//$values = [1, 3, 4];
		//$country = DB::model('Country')->whereIn('id', $values)->all();
		// $country = DB::model('Country')->order('name')->all();
		//$country = DB::model('Country')->setUpsertConstraint('countries_name_key')->upsert(['name' => 'Japan', 'is_provide' => true]);
		//$count = DB::model('Country')->count('name');
		//$country = DB::model('Country')->where('area', 'Asia')->one();
		//$country = DB::model('Country')->select(['name'])->fetch(1);
		//$version = DB::model('Country')->pgVersion();

		//$country = DB::model('Country')->fetch(1);
		//$user = $country->relation('User')->all();
		//$user = $country->relation('User', 'country_id', 'id')->all();
		//var_export($user->values);

		// $user = DB::model('User')->fetch(5);
		// $country = $user->belongsTo('Country');
		// var_export($country->value);
		// var_export($country->sql);
		//$user = DB::model('User')->fetch(5)->bindBelongsTo('Country');

	}

    function action_edit() {

	}

	function action_inserts_users() {
        $values[] = ['name' => 'Yamada', 'country_id' => 1];
        $values[] = ['name' => 'Ito', 'country_id' => 1];
		$values[] = ['name' => 'Sato', 'country_id' => 1];
        $values[] = ['name' => 'Mike', 'country_id' => 2];
        $values[] = ['name' => 'Martin', 'country_id' => 3];
		$values[] = ['name' => 'Mario', 'country_id' => 4];

		$country = DB::table('User');
		$country->inserts($values);
		var_export($country->sql);
		var_export($country->sql_error);
	}

	function action_inserts_countries() {
        $values[] = ['name' => 'USA', 'sort_order' => 2];
        $values[] = ['name' => 'France', 'sort_order' => 3];
		$values[] = ['name' => 'Italy', 'sort_order' => 4];
		$values[] = ['name' => 'China', 'sort_order' => 5];

		$country = DB::table('Country');
		$country->inserts($values);
		var_export($country->sql);
		var_export($country->sql_error);
	}

	function action_update_sort_countries() {
		//primary id
		$_REQUEST['sort_order'][] = 2; 
		$_REQUEST['sort_order'][] = 1; 
		$_REQUEST['sort_order'][] = 4; 
		$_REQUEST['sort_order'][] = 3; 

        $this->updateSort('Country', false);

		$country = DB::table('Country');
		$country->all();
		var_export($country->values);
	}

	function action_delete_user_records() {
		$country = DB::table('User');
		$country->deleteRecords();
	}

	function action_delete_records() {
		$country = DB::table('Country');
		$country->deleteRecords();
	}

	/**
	 * form helper
	 *
	 * @return void
	 */
    function action_form_ex() {
        $values = null;
        $values[] = array('id' => 1, 'name' => 'Tokyo', 'country' => 'Japan');
        $values[] = array('id' => 2, 'name' => 'Osaka', 'country' => 'Japan');
        $values[] = array('id' => 3, 'name' => 'Nagoya', 'country' => 'Japan');
        $values[] = array('id' => 4, 'name' => 'NewYork', 'country' => 'USA');
        $values[] = array('id' => 5, 'name' => 'Paris', 'country' => 'France');

        $this->forms['area']['id'] = 'area';
        $this->forms['area']['class'] = 'form-control';
        $this->forms['area']['name'] = 'select[area]';
        $this->forms['area']['value_key'] = 'id';
        $this->forms['area']['label'] = ['country', 'name'];
        $this->forms['area']['label_separate'] = ' : ';
        $this->forms['area']['values'] = $values;
        $this->forms['area']['unselect']['label'] = "-------";
        $this->forms['area']['unselect']['value'] = -1;

        $this->forms['area_radio'] = $this->forms['area'];
        $this->forms['area_radio']['id'] = 'area_radio';
        $this->forms['area_radio']['name'] = 'radio[area]';
        $this->forms['area_radio']['class'] = 'form-control';

        $this->forms['area_checkbox'] = $this->forms['area'];
        $this->forms['area_checkbox']['id'] = 'area_checkbox';
        $this->forms['area_checkbox']['name'] = 'checkbox[area]';
        $this->forms['area_checkbox']['class'] = 'form-control';
        $this->forms['area_checkbox']['div_class'] = 'form-check';
        $this->forms['area_checkbox']['value_key'] = 'id';
        $this->forms['area_checkbox']['label_key'] = 'value';

        //select_date
        $this->forms['date']['name'] = 'date';
        $this->forms['date']['selected']['year'] = date('Y');
        $this->forms['date']['selected']['month'] = date('m');
        $this->forms['date']['selected']['day'] = date('d');
        $this->forms['date']['years'] = range(date('Y') + 1,  date('Y') - 10);
        $this->forms['date']['class'] = 'form-control action-change-date';
        $this->forms['date']['formatter'] = 'j';
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
	
	function action_pw_sortable() {

	}

	function action_update_sort() {
        $origin = '*';
        header("Access-Control-Allow-Origin: {$origin}");
		header("Access-Control-Allow-Headers: X-Requested-With");

		$results['is_success'] = true;
		$results['post'] = $_POST;
		$this->renderJson($results);
	}

}
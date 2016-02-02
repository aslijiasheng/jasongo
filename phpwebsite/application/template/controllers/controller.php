<?php
class {#obj_name_uc#} extends WWW_Controller{
	public $menu1='{#obj_name#}';

	public function __construct(){
		parent::__construct();
		$this->load->model('www/{#obj_name#}_model','{#obj_name#}');
	}
{#function_all#}

}
?>
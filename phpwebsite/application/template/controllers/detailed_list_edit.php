
	public function detailed_list_edit(){
		$this->{#obj_name#}->db->query('SET NAMES UTF8');
		$data['listData']=$this->{#obj_name#}->listGetInfo('',1,10);
		$data["labels"]=$this->{#obj_name#}->attributeLabels();
		$data['listLayout']=$this->{#obj_name#}->listLayout();
		//$this->output->enable_profiler(false);
		$this->load->view('www/{#obj_name#}/detailed_list_edit',$data);
	}
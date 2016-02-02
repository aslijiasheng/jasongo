
	public function view(){
		$this->{#obj_name#}->db->query('SET NAMES UTF8');
        $data["labels"]=$this->{#obj_name#}->attributeLabels();
		$data['listLayout']=$this->{#obj_name#}->id_aGetInfo($_GET['{#obj_name#}_id']);
		$this->render('www/{#obj_name#}/view',$data);
	}
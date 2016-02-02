
	public function update(){
		$this->{#obj_name#}->db->query('SET NAMES UTF8');
		$data["labels"]=$this->{#obj_name#}->attributeLabels();
		$data['id_aData']=$this->{#obj_name#}->id_aGetInfo($_GET['{#obj_name#}_id']);
		$data['listLayout']=$this->{#obj_name#}->listLayout();
		{#foreach from=obj_attr item=oa key=oa_k#}
		{#if {#oa|attr_type#}==14#}
		$this->load->model('admin/enum_model','enum');
		$data["{#obj_name#}_{#oa|attr_name#}_enum"]=$this->enum->getlist({#oa|attr_id#});{#/if#}{#if {#oa|attr_type#}==15#}
		$this->load->model('admin/enum_model','enum');
		$data["{#obj_name#}_{#oa|attr_name#}_enum"]=$this->enum->getlist({#oa|attr_id#});{#/if#}{#if {#oa|attr_type#}==16#}
		$this->load->model('admin/enum_model','enum');
		$data["{#obj_name#}_{#oa|attr_name#}_enum"]=$this->enum->getlist({#oa|attr_id#});{#/if#}
		{#/foreach#}
		if(!empty($_POST)){
		    if(isset($_GET['type_id'])){
		      $_POST['{#obj_name#}']['type_id']=$_GET['type_id'];
		      $this->{#obj_name#}->update($_POST['{#obj_name#}'],$_GET['{#obj_name#}_id']);
			    success('www/{#obj_name#}?type_id='.$_GET['type_id'],'更新成功');
		    }else{
              	$this->{#obj_name#}->update($_POST['{#obj_name#}'],$_GET['{#obj_name#}_id']);
			    success('www/{#obj_name#}','更新成功');
		    }
		}
		$this->render('www/{#obj_name#}/update',$data);
	}
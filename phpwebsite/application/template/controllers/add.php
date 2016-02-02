
	public function add(){
		$this->{#obj_name#}->db->query('SET NAMES UTF8');
		$data["labels"]=$this->{#obj_name#}->attributeLabels();
		{#foreach from=obj_attr item=oa key=oa_k#}
		{#if {#oa|attr_type#}==14#}
		$this->load->model('admin/enum_model','enum');$data["{#obj_name#}_{#oa|attr_name#}_enum"]=$this->enum->getlist({#oa|attr_id#});{#/if#}{#if {#oa|attr_type#}==15#}
		$this->load->model('admin/enum_model','enum');$data["{#obj_name#}_{#oa|attr_name#}_enum"]=$this->enum->getlist({#oa|attr_id#});{#/if#}{#if {#oa|attr_type#}==16#}
		$this->load->model('admin/enum_model','enum');$data["{#obj_name#}_{#oa|attr_name#}_enum"]=$this->enum->getlist({#oa|attr_id#});{#/if#}
		{#/foreach#}
		if(!empty($_POST)){
		if(isset($_GET['type_id'])){
           $_POST['{#obj_name#}']['type_id']=$_GET['type_id'];
           $this->{#obj_name#}->add($_POST['{#obj_name#}']);
		    success('www/{#obj_name#}?type_id='.$_GET['type_id'],'创建成功');
		}else{
		  $this->{#obj_name#}->add($_POST['{#obj_name#}']);
		  success('www/{#obj_name#}','创建成功');
		}
			
		}
		$this->render('www/{#obj_name#}/add',$data);
	}
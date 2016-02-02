
	public function ajax_select_quote(){
		if (isset($_POST["select_json"]) and $_POST["select_json"]!=""){
			$select_arr = json_decode($_POST["select_json"],true);
			$like["{#obj_name#}_".$select_arr['attr']]=$select_arr['value'];
		}else{
			//$select_arr = "";
			$like="";
		}
		if (!isset($_GET['page'])){
			$_GET['page']=1;
		}
		if (!isset($_GET['perNumber'])){
			$_GET['perNumber']=10;
		}
		$where="";
		if (isset($_GET['type_id'])){ 
			if($_GET['type_id']!="" and $_GET['type_id']!=0){
				$where['type_id']=$_GET['type_id']; 
			}
		}
		
		$data['listData']=$this->{#obj_name#}->listGetInfo($where,$_GET['page'],$_GET['perNumber'],$like);
		$data["labels"]=$this->{#obj_name#}->attributeLabels();
		//如果包含对象类型，添加一个数组，用于视图获取
		
		{#if {#is_obj_type#}==1#}
		{#foreach1 from=type_arr item=ta key=ta_k#}
		{#foreach2 from=list_layout.layout_arr item=lll key=lll_k#}
			$data['typelistlayout']['{#ta|type_id#}'][]='{#obj_name#}_{#lll|attr_name#}';
		{#/foreach2#}
		{#/foreach1#}
		{#/if#}

		if (!isset($_GET['perNumber'])){
			$_GET['perNumber']=10;
		}
		$data['totalNumber'] = $this->{#obj_name#}->countGetInfo($where,$like); //数据总数
		$data['perNumber'] = $_GET['perNumber']; //每页显示的记录数
		//p($data);
		$this->output->enable_profiler(false);
		$this->load->view('www/{#obj_name#}/ajax_select_quote',$data);
	}
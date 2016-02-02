
	public function index(){
		//根据类型给个菜单标签
		$this->{#obj_name#}->db->query('SET NAMES UTF8');
		if (!isset($_GET['page'])){
			$_GET['page']=1;
		}
		if (!isset($_GET['perNumber'])){
			$_GET['perNumber']=10;
		}
		//如果包含类型则生成一个关于类型的数组
		//$data['type_arr'];
		{#if {#is_obj_type#}==1#}
		{#foreach from=type_arr item=ta key=ta_k#}
		$data['type_arr']['{#ta|type_id#}']["type_id"]='{#ta|type_id#}';
		$data['type_arr']['{#ta|type_id#}']["type_name"]='{#ta|type_name#}';
		if(isset($_GET['type_id'])){
			if($_GET['type_id']=={#ta|type_id#}){
				$this->menu1='{#ta|type_label#}';
			}
		}
		{#/foreach#}
		{#/if#}
		$data['totalNumber'] = $this->{#obj_name#}->countGetInfo(); //数据总数
		$data['perNumber'] = $_GET['perNumber']; //每页显示的记录数
		$data["labels"]=$this->{#obj_name#}->attributeLabels();
		$data['listLayout']=$this->{#obj_name#}->listLayout();
		$this->render('www/{#obj_name#}/list',$data);
	}
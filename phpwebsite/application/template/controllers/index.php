
	public function index(){
		$this->{#obj_name#}->db->query('SET NAMES UTF8');
		if (!isset($_GET['page'])){
			$_GET['page']=1;
		}
		if (!isset($_GET['perNumber'])){
			$_GET['perNumber']=10;
		}
		$data['totalNumber'] = $this->{#obj_name#}->countGetInfo(); //数据总数
		$data['perNumber'] = $_GET['perNumber']; //每页显示的记录数
		$data["labels"]=$this->{#obj_name#}->attributeLabels();
		$data['listLayout']=$this->{#obj_name#}->listLayout();
		$this->render('www/{#obj_name#}/list',$data);
	}
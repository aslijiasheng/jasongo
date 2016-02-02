
	public function ajax_add(){
		$this->salespay->db->query('SET NAMES UTF8');
		if(isset($_GET['order_id'])){
			$this->load->model('www/order_model','order');
			$data["order_data"]=$this->order->id_aGetInfo($_GET['order_id']);
			$data["labels"]=$this->salespay->attributeLabels();
			$this->load->model('admin/enum_model','enum');$data["salespay_pay_method_enum"]=$this->enum->getlist(144);
			$this->load->model('admin/enum_model','enum');$data["salespay_status_enum"]=$this->enum->getlist(146);
			$this->load->view('www/salespay/ajax_add',$data);
		}else{
			echo "不能没有ID";
		}
	}
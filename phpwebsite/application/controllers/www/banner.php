<?php
Class Banner extends WWW_controller{

    private $portalDB;

	function __construct(){
		parent::__construct();
        $this->portalDB = $this->load->database('portal', TRUE);
        $this->load->model('www/banner_model', 'banner');
	}

	public function images(){
        $ret['data'] = $this->banner->queryBanner(TRUE);
		$this->render('www/portal/images',$ret);
	}

    public function delBanner(){
        //使用Intval防止注入
        $portalId = intval($this->input->post('portalId'));
        $ret = $this->banner->delBanner($portalId);
        echo $ret;
        die;
    }

    public function multifile(){
        $data['url'] = site_url('www/portal/multiFiles');
        $this->load->view('www/objects/imagesupload', $data);
    }

    public function multiFiles(){
        $fileName = $_FILES['file']['name'];
        $fileTmpDir = $_FILES['file']['tmp_name'];
        $streamReader = file_get_contents($fileTmpDir);
        file_put_contents("images/bannerImages/{$fileName}", $streamReader);
        $bannerPic = base_url() . "images/bannerImages/{$fileName}";
        $bannerAddSql = "insert into tc_banner(banner_pic, banner_is_status) values('{$bannerPic}', 'TRUE')";
        $ret = $this->portalDB->query($bannerAddSql);
        die($ret);
    }
}
?>

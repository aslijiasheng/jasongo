<?php
Class Portal extends WWW_controller{

    private $portalDB;
    private static $htmlPath = "htmltemplete/announcement/@.html";

	function __construct(){
		parent::__construct();
        $this->portalDB = $this->load->database('portal', TRUE);
        $this->load->model('www/banner_model', 'banner');
        $this->load->model('www/announcement_model', 'announcementModel');
        $this->load->model('www/marteria_model', 'marteria');
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

    /**
     * ckeditMultiFiles 
     * 富文本编辑器上传
     * @access public
     * @return void
     */
    public function ckeditMultiFiles(){
        $message      = '';
        $fileName     = $_FILES['upload']['name'];
        $fileTmpDir   = $_FILES['upload']['tmp_name'];
        $funcNum      = $_GET['CKEditorFuncNum'];
        $CKEditor     = $_GET['CKEditor'];
        $langCode     = $_GET['langCode'];
        $retWritePath = $this->marteria->saveMarteriaPath($fileTmpDir, $fileName);
        switch($retWritePath){
            case 'EXTENSION IS FALSE':
                $message                            = 'fileExtension is error';
                break;
            default:
                $writerPic                          = base_url() . $retWritePath;
                $marteriaData                       = array();
                $marteriaData['marteria_path']      = $writerPic;
                $marteriaData['marteria_is_status'] = 'TRUE';
                $marteriaID                         = $this->marteria->addMarteria($marteriaData);
                if(!$marteriaID) $message           = 'marteriaData add is FALSE';
                break;
        }
        /**
         *  回调给前端记录产生成的图片ID
         */
        echo "<script type='text/javascript'>window.parent.innerAnnouncement({$marteriaID});</script>";
        /**
         * 回调给富文本编辑器显示上传过后的图片 
         */
        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$writerPic', '$message');</script>";
    }

    /**
     * announcement 
     * 公告管理
     * @access public
     * @return void
     */
    public function announcement(){
        $announcementData = $this->announcementModel->getAnnouncementData();
        $this->render('www/portal/announcement', $announcementData);
    }

    /**
     * addAnnouncement 
     * 新增公告管理
     * @access public
     * @return void
     */
    public function addAnnouncement(){
        $announcementTitle      = $this->input->post("announcement_title");
        $announcementPic        = $this->input->post("announcement_pic");
        $announcementCreateDate = strtotime($this->input->post("announcement_create_date"));
        $announcementTag        = $this->input->post("announcement_tag");
        $announcementHtml       = $this->input->post("announcement_html");
        $announcementIsStatus   = intval($this->input->post("announcement_is_status"));
        $announcementData = array(
            "announcement_title"       => $announcementTitle,
            "announcement_pic"         => $announcementPic,
            "announcement_create_date" => $announcementCreateDate,
            "announcement_tag"         => $announcementTag,
            "announcement_html_path"   => self::htmlPath,
            "announcement_is_status"   => $announcementIsStatus,
        );
        $ret = $this->announcementModel->addAnnouncement($announcementData);
        if($ret){
            $announcementHtmlPath   = self::announcementSaveHtmlPath($ret, $announcementHtml);
            $announcementUpData = array(
                "announcement_html_path"   => $announcementHtmlPath,
            );
            $ret = $this->announcementModel->updateAnnouncement($ret, $announcementUpData);
        }
        echo $ret;
        die;
    }

    public function delAnnouncement(){
        $announcementID = intval($this->input->post('announcement_id'));
        $ret = $this->announcementModel->updateAnnouncement($announcementID, array('announcement_is_status', FALSE));
        die($ret);
    }

    public function viewAnnouncement(){
        $announcementID = intval($this->input->post('announcement_id'));
        $announcementData = $this->announcementModel->getAnnouncementData($announcementID);
        $this->load->view('www/portal/viewAnnouncement', $announcementData);
        die;
    }

    public function editAnnouncement(){
        $announcementID         = intval($this->input->post("announcement_id"));
        $announcementTitle      = $this->input->post("announcement_title");
        $announcementPic        = $this->input->post("announcement_pic");
        $announcementCreateDate = strtotime($this->input->post("announcement_create_date"));
        $announcementTag        = $this->input->post("announcement_tag");
        $announcementHtml       = $this->input->post("announcement_html");
        $announcementIsStatus   = intval($this->input->post("announcement_is_status"));
        $announcementHtmlPath   = self::announcementSaveHtmlPath($announcementID, $announcementHtml);
        $announcementData = array(
            "announcement_title"       => $announcementTitle,
            "announcement_pic"         => $announcementPic,
            "announcement_create_date" => $announcementCreateDate,
            "announcement_tag"         => $announcementTag,
            "announcement_html_path"   => $announcementHtmlPath,
            "announcement_is_status"   => $announcementIsStatus,
        );
        $ret = $this->announcementModel->updateAnnouncement($announcementID, $announcementData);
        echo $ret;
        die;
    }

    /**
     * announcementSaveHtmlPath 
     * 将获取的富文本数据写入txt或者html文件中只入库id
     * @param mixed $announcementID 
     * @param mixed $announcementHtml 
     * @access private
     * @return void
     */
    private static function announcementSaveHtmlPath($announcementID, $announcementHtml){
        file_put_contents("htmltemplete/announcement/{$announcementID}.html", $announcementHtml);
        return "htmltemplete/announcement/{$announcementID}.html";
    }
}
?>

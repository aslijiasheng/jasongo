<?php
Class Announcement extends WWW_controller{

    private static $htmlPath = "htmltemplete/announcement/@.html";

	function __construct(){
		parent::__construct();
        $this->load->model('www/announcement_model', 'announcementModel');
        $this->load->model('www/marteria_model', 'marteria');
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
                $marteriaData['marteria_is_status'] = TRUE;
                $marteriaID                         = $this->marteria->addMarteria($marteriaData);
                if(!$marteriaID) $message           = 'marteriaData add is FALSE';
                break;
        }
        echo "<script type='text/javascript'>innerAnnouncement({$marteriaID});window.parent.CKEDITOR.tools.callFunction($funcNum, '$bannerPic', '$message');</script>";
    }

    /**
     * announcement 
     * 公告管理
     * @access public
     * @return void
     */
    public function viewAction(){
        $announcementData = $this->announcementModel->getAnnouncementData();
        $this->render('www/portal/announcement', array('announcementData' => $announcementData));
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
        $announcementCreateDate = strtotime(date('Y-m-d'));
        $announcementTag        = $this->input->post("announcement_tag");
        $announcementHtml       = $this->input->post("announcement_html");
        $announcementCreateUserName = empty($this->input->post("announcement_create_user")) ? $this->session->userdata['user_name'] : $this->input->post("announcement_create_user");
        $announcementIsStatus   = self::converStatus(1);
        $announcementData = array(
            "announcement_title"       => $announcementTitle,
            "announcement_pic"         => $announcementPic,
            "announcement_create_date" => $announcementCreateDate,
            "announcement_tag"         => $announcementTag,
            "announcement_html_path"   => self::$htmlPath,
            "announcement_is_status"   => $announcementIsStatus,
            "announcement_create_user" => $announcementCreateUserName,
        );
        $ret = $this->announcementModel->addAnnouncement($announcementData);
        if($ret){
            $announcementHtmlPath   = self::announcementSaveHtmlPath($ret, $announcementHtml);
            $announcementUpData = array(
                "announcement_html_path"   => $announcementHtmlPath,
            );
            $ret = $this->announcementModel->updateAnnouncement($ret, $announcementUpData);
        }
        $this->sender($ret);
        die;
    }

    public function delAnnouncement(){
        $announcementID = intval($this->input->post('announcement_id'));
        $ret = $this->announcementModel->updateAnnouncement($announcementID, array('announcement_is_status' => 'FALSE'));
        die($ret);
    }

    public function viewAnnouncement(){
        $announcementData = array();
        $announcementID = intval($this->input->post('announcement_id'));
        if(!empty($announcementID))
            $announcementData = $this->announcementModel->getAnnouncementData($announcementID);
        var_dump($announcementData);
        $this->load->view('www/portal/viewAnnouncement', array('announcementID' => $announcementID, 'announcementData' => $announcementData));
    }

    /**
     * reviewAnnouncement 
     * 预览
     * @access public
     * @return void
     */
    public function reviewAnnouncement(){
        $announcementData = array();
        $announcementID = intval($this->input->post('announcement_id'));
        if(!empty($announcementID))
            $announcementData = $this->announcementModel->getAnnouncementData($announcementID);
        var_dump($announcementData);
        $this->load->view('www/portal/reviewAnnouncement', array('announcementID' => $announcementID, 'announcementData' => $announcementData));
    }

    public function editAnnouncement(){
        $announcementID             = intval($this->input->post("announcement_id"));
        $announcementTitle          = $this->input->post("announcement_title");
        $announcementPic            = $this->input->post("announcement_pic");
        $announcementCreateDate     = strtotime(date('Y-m-d'));
        $announcementTag            = $this->input->post("announcement_tag");
        $announcementHtml           = $this->input->post("announcement_html");
        $announcementCreateUserName = empty($this->input->post("announcement_create_user")) ? $this->session->userdata['user_name'] : $this->input->post("announcement_create_user");
        $announcementIsStatus       = self::converStatus(1);
        $announcementHtmlPath       = self::announcementSaveHtmlPath($announcementID, $announcementHtml);
        $announcementData = array(
            "announcement_title"       => $announcementTitle,
            "announcement_pic"         => $announcementPic,
            "announcement_create_date" => $announcementCreateDate,
            "announcement_tag"         => $announcementTag,
            "announcement_html_path"   => $announcementHtmlPath,
            "announcement_is_status"   => $announcementIsStatus,
            "announcement_create_user" => $announcementCreateUserName,
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

    /**
     * converStatus 
     * 状态转换
     * @param mixed $announcementStatus 
     * @static
     * @access private
     * @return void
     */
    private static function converStatus($announcementStatus){
        switch($announcementStatus){
            case 0:
                return FALSE;
                break;
            case 1:
                return TRUE;
                break;
        }
    }
}
?>

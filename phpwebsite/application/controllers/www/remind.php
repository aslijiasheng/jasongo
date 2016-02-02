<?php
class Remind extends WWW_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('www/lead_model', 'lead');
        $this->load->model('www/objects_model', 'objects');
        $this->load->model('admin/obj_model', 'obj');
        $this->load->model('admin/format_model', 'format');
        $this->load->model('admin/attr_model', 'attr');
        $this->load->model('www/remind_model','remind');
        $this->load->model('www/message_model','message');
    }
    public function index(){
    	$this->Desktopreminderlist();
    }

    public function lists() {
        $OrgID = $this->session->userdata('org_id');
        $ObjType = $_GET['obj_type']; //接收传递的模块类型值
        $UserID = $this->session->userdata('user_id');
        $obj = $this->obj->getOneData($ObjType);
        $obj_name = $obj['OBJ_NAME'];
        $data = $this->getLists($obj_name, __FUNCTION__);

        $data["objName"] = $this ->obj-> getListName($obj_name);

        $data["ObjType"] = $ObjType;

        $data = $this ->objects-> pub_button($OrgID,$data);
        $data['message'] = $this->message->countn4($UserID);
        
        $data['ColModel']['Col'][0]['Width']=700;
        $data['ColModel']['Col'][0]['Url']="{REMIND.url}";

        $this->render('www/remind/list', $data);

    }

    public function getLists($obj_name, $func_name) {
        $data['menu_active'] = 3; //选中的menu_id
        $org_id = $this->session->userdata('org_id');
        $LangID = $this->session->userdata('lang_id');
        $user_id = $this->session->userdata('user_id');
        $SeniorQueryAttrs = array(
            "org_id" => $org_id,
            "LangID" => $LangID,
            "obj_name" => $obj_name,
            "user_id" => $user_id,
        );
        return $this->remind->getListsAttr($SeniorQueryAttrs);
    }
    //桌面提醒
    public function DesktopReminderList(){
        $user_id = $this->session->userdata('user_id');
        $org_id = $this->session->userdata('org_id');
        $obj_data = $this->obj->NameGetObj($org_id, 'Reminder');
        $reminder_attr = $this->attr->object_attr($org_id, 'Reminder');
        //p($reminder_attr);
        $data = $this->remind->ReminderList($org_id,$user_id,true);
        echo $data;
        die;
    }
    //提醒列表
    public function ReminderList(){
        $user_id = $this->session->userdata('user_id');
        $org_id = $this->session->userdata('org_id');
        $obj_data = $this->obj->NameGetObj($org_id, 'Reminder');
        $reminder_attr = $this->attr->object_attr($org_id, 'Reminder');
        //p($reminder_attr);
        $CurrentPage = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $PageSize = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : 10;
        $data = $this->remind->ReminderList($org_id,$user_id,false,$CurrentPage,$PageSize);

        echo $data;
        die;
    }
    public function add(){
        
        $this->remind->AddRemind(3,1,1,2046116);
    }
}

?>
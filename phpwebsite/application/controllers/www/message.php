<?php
class Message extends WWW_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('www/lead_model', 'lead');
        $this->load->model('www/objects_model', 'objects');
        $this->load->model('admin/obj_model', 'obj');
        $this->load->model('admin/format_model', 'format');
        $this->load->model('admin/attr_model', 'attr');
        $this->load->model('www/message_model','message');
        $this->load->model('www/remind_model','remind');
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
        $this->render('www/message/list', $data);

    }
    /**
     * 公用列表页面列表显示区域
     * @params string $obj_name 对象名称
     * @return array  返回查询出来的对象结果集
     */
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
            "func_name" => $func_name,
        );
        return $this->message->getListsAttr($SeniorQueryAttrs);
    }
    //消息中心 暂时先放着
    public function list_json(){
        $obj_type = 170;//$_POST['obj_type'];
        $user_id = $this->session->userdata('user_id');
        
        $org_id = $this->session->userdata('org_id');
        $obj = $this->obj->getOneData($obj_type);
        $obj_name = $obj['OBJ_NAME'];
        $format = $this->format->GetListFormat($org_id, $obj_type, 0);
        $obj_data = $this->obj->NameGetObj($org_id, $obj_name);
        $where['where'] = isset($_POST['seniorquery']['where']) ? $_POST['seniorquery']['where'] : null; //高级查询条件
        $where['rel'] = isset($_POST['seniorquery']['rel']) ? $_POST['seniorquery']['rel'] : null; //高级查询公式
        if (empty($where['where']) && empty($where['rel'])) {
            $where = null;
        }
        $CurrentPage = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $PageSize = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : 10;
        extract($obj_data, EXTR_OVERWRITE); //释放数组元素
        //获取列表需要显示的字段
        //查询列表数据
        $lead_attr = $this->attr->object_attr($org_id, $obj_name);
        $list_data = $this->message->InfoPagerList($format, $lead_attr, $obj_data, $org_id, $user_id,$CurrentPage, $PageSize,$where);
        echo $list_data;
        
        die;
    }
        public function view() {

        $message_id = $_GET['id'];
        $data['id'] = $message_id;
        $LangID = $this->session->userdata('lang_id');
        $user_id = $this->session->userdata('user_id');
        $ObjType = $_GET['obj_type'];

        $data['obj_type'] = $ObjType;
        $obj = $this->obj->getOneData($ObjType);
        $obj_name = $obj['OBJ_NAME'];
        $data['OBJ_NAME'] = $obj_name;

        $org_id = $this->session->userdata('org_id');
        $obj_data = $this->obj->NameGetObj($org_id, $obj_name);
        $obj_dict = $this->obj->getListName($obj_name,$LangID);
        extract($obj_data, EXTR_OVERWRITE);
        //查询
        $lead_attr = $this->attr->object_attr($org_id, $obj_name);

        $DictAttr = _HashData($this->obj->NameGetCnDict($org_id, $LangID), "DICT_NAME");

        $data['lead_data'] = $this->objects->Getinfo($obj_name, $org_id, $DictAttr, $lead_attr, $message_id);
        $data['message'] = $this->message->countn4($user_id);

        //获取需要显示的字段

        $format = Array(
            "objType" => 170,
            "classID" => 16,
            "userID" => 0,
            "tables" => Array(
                0 => Array(
                    "tableNames" => Array(
                        1 => "消息详细",
                        2 => "消息详细",
                        3 => "消息详细",
                        ),

                    "columns" => 2,
                    "cells" => Array(
                        0 => Array(
                            "name" => "MessageItem.Type",
                            "rowspan" => 1,
                            "colspan" => 1,
                            "label" => 1,
                            "required" => null,
                            ),

                        1 => Array(
                            "name" => "MessageItem.Type",
                            "rowspan" => 1,
                            "colspan" => 1,
                            "label" => 0,
                            "required" =>null,
                            ),
                        2 => Array(
                            "name" => "MessageItem.To",
                            "rowspan" => 1,
                            "colspan" => 1,
                            "label" => 1,
                            "required" =>null,
                            ),
                        3 => Array(
                            "name" => "MessageItem.To",
                            "rowspan" => 1,
                            "colspan" => 1,
                            "label" => 0,
                            "required" =>null,
                            ),
                        4 => Array(
                            "name" => "MessageItem.Subject",
                            "rowspan" => 1,
                            "colspan" => 1,
                            "label" => 1,
                            "required" =>null,
                            ),
                        5 => Array(
                            "name" => "MessageItem.Subject",
                            "rowspan" => 1,
                            "colspan" => 1,
                            "label" => 0,
                            "required" =>null,
                            ),
                        6 => Array(
                            "name" => "MessageItem.CreatedTime",
                            "rowspan" => 1,
                            "colspan" => 1,
                            "label" => 1,
                            "required" =>null,
                            ),
                        7 => Array(
                            "name" => "MessageItem.CreatedTime",
                            "rowspan" => 1,
                            "colspan" => 1,
                            "label" => 0,
                            "required" =>null,
                            )    
                        )
                    )
                )
            );

        $data['format_data'] = $this->objects->GetInfoView($org_id, $format, $data['lead_data'], $lead_attr, $message_id);
        $this->message->read_flag($message_id,$org_id,$user_id);
        //$objTypeArr 表示线索转化需要的key去查询相应的显示字段适合以后扩展
        $data["obj_type_arr"] = array(
            "1",
            "2",
            "4"
        );
        $data["obj_type_data"] = $this->lead->GetObjTypeData($data["obj_type_arr"]);
        $data = $this ->objects-> view_pub_button($this->session->userdata('org_id'),$data);
        $data['dict'] = $obj_dict[0]['LABEL'];
        $this->render('www/message/view', $data);
    }

    public function add() {
        if(empty($_GET['type_id'])){
            $TypeID=0;
        }else{
            $TypeID = $_GET['type_id'];
        }
        
        $org_id = $this->session->userdata('org_id');
        $LangID = $this->session->userdata("lang_id");
        $ObjType = $_GET['obj_type'];
        $obj = $this->obj->getOneData($ObjType);
        $obj_name = $obj['OBJ_NAME'];
        $obj_data = $this->obj->NameGetObj($org_id, $obj_name);
        p($obj_data);
        extract($obj_data, EXTR_OVERWRITE);
        $user_id = $this->session->userdata('user_id');
        $lead_attr = $this->attr->object_attr($org_id, $obj_name);

        $enum_attr = $this->enum->SelectAll();
        $DictAttr = _HashData($this->obj->NameGetCnDict($org_id, $LangID), "DICT_NAME");
        $data['lead_data'] = $this->message->Getinfo($org_id, $lead_attr, $user_id,$TypeID);

        //获取需要显示的字段
        $format = Array(
            "objType" => 170,
            "classID" => 16,
            "userID" => 0,
            "tables" => Array(
                0 => Array(
                    "tableNames" => Array(
                        1 => "消息详细",
                        2 => "消息详细",
                        3 => "消息详细",
                        ),

                    "columns" => 2,
                    "cells" => Array(
                        0 => Array(
                            "name" => "MessageItem.Type",
                            "rowspan" => 1,
                            "colspan" => 1,
                            "label" => 1,
                            "required" => null,
                            ),

                        1 => Array(
                            "name" => "MessageItem.Type",
                            "rowspan" => 1,
                            "colspan" => 1,
                            "label" => 0,
                            "required" =>null,
                            ),
                        2 => Array(
                            "name" => "MessageItem.To",
                            "rowspan" => 1,
                            "colspan" => 1,
                            "label" => 1,
                            "required" =>null,
                            ),
                        3 => Array(
                            "name" => "MessageItem.To",
                            "rowspan" => 1,
                            "colspan" => 1,
                            "label" => 0,
                            "required" =>null,
                            ),
                        4 => Array(
                            "name" => "MessageItem.Subject",
                            "rowspan" => 1,
                            "colspan" => 1,
                            "label" => 1,
                            "required" =>null,
                            ),
                        5 => Array(
                            "name" => "MessageItem.Subject",
                            "rowspan" => 1,
                            "colspan" => 1,
                            "label" => 0,
                            "required" =>null,
                            ),
                        6 => Array(
                            "name" => "MessageItem.CreatedTime",
                            "rowspan" => 1,
                            "colspan" => 1,
                            "label" => 1,
                            "required" =>null,
                            ),
                        7 => Array(
                            "name" => "MessageItem.CreatedTime",
                            "rowspan" => 1,
                            "colspan" => 1,
                            "label" => 0,
                            "required" =>null,
                            )    
                        )
                    )
                )
            );
        
        $data['format_data'] = $this->objects->GetInfoEdit($org_id, $format, $data['lead_data'], $lead_attr, "", $enum_attr);
        $data['message'] = $this->message->countn4($user_id);
        $data['obj_type'] = $ObjType;
        $this->render('www/messsage/add', $data);
    }
    public function save(){
        $post = @$_POST;
        foreach ($post['data'] as $k => $v) {
            $post_data[$v['name']]=$v['value'];
        }
        
        $user_id = $this->session->userdata('user_id');
        $data = $this->remind->InsertMessage($org_id,$user_name,$name,$user_id,$event_name="",$obj_type="",$obj_id="");
        return $data;
    }

}

?>
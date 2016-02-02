<?php

class Api extends CI_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('www/lead_model', 'lead');
        $this->load->model('www/user_model', 'user');
        $this->load->model('www/objects_model', 'objects');
        $this->load->model('admin/obj_model', 'obj');
        $this->load->model('admin/format_model', 'format');
        $this->load->model('admin/attr_model', 'attr');
        $this->load->model('admin/org_model', 'org');
    }

    public function index() {
        //用户验证->请求对象验证->请求接口类型验证->用户权限验证
        
        $LoginName = $_POST["LoginName"];
        $LoginPassword = $_POST["LoginPassword"];
        $OrgName = $_POST["OrgName"];
        $ObjName = $_POST["ObjectName"];
        $data = $_POST["Data"];
        $Type = $_POST["Type"];
        $PageSize =$_POST['PageSize'];
        $data = $this->JsonToArray($data);
        
        //用户验证
        $OrgID = $this->org->NameGetId($OrgName);
        if(!empty($OrgID)){
            $Userdata = $this->user->LoginUserData($LoginName,$Org);
            if(!empty($Userdata) && count($Userdata) == 1){
                $LoginPassword=$this->user->encodePassword($LoginPassword);
                if($Userdata[0]['LOGIN_PASSWORD'] == $LoginPassword){
                    //请求对象名验证
                    $Objdata = $this->obj->NameGetObj($OrgID, $ObjName);
                    if(!empty($Objdata) && $Objdata['IS_RECYCLABLE']){
                        //接口类型验证
                        if(!empty($Type)){
                            switch ($Type) {
                                case 'list':
                                    return $this->lists($OrgID,$Objdata,$Userdata[0]['USER_ID'], $data,$PageSize);
                                    break;
                                case 'add':
                                    $premission = $this->objects->premissionAct($Objdata, $OrgID, $Userdata[0]['USER_ID'], $Type);
                                    if($premission){
                                        return $this->add($OrgID,$Objdata,$data,$Userdata[0]['USER_ID']);
                                    }else{
                                        return $this->api_error('200');//该用户没有权限
                                    }
                                    break;
                                case 'update':
                                    return $this->update($OrgID,$Objdata,$data,$Userdata[0]['USER_ID']);
                                    break;
                                case 'del':
                                    return $this->del($OrgID,$Objdata,$data,$Userdata[0]['USER_ID']);
                                    break;
                                default:
                                    return $this->api_error('102');//未知类型
                                    break;
                            }
                        }else{
                            return $this->api_error('101');//接口类型为空
                        }
                    }else{
                        return $this->api_error('106');//请求对象不存在
                    }
                }else{
                    return $this->api_error('105');//密码错误
                }
            }else{
                return $this->api_error('103');//用户不存在
            }
        }else{
            return $this->api_error('104');//单位简称不存在
        }
    }
    
    public function JsonToArray($data){
        $data=json_decode($data);
        if(is_object($data)){
            $data = (array)$data;
        }
        if(is_array($data)){
            foreach($data as $key=>$value){
                $data[$key] = object_array($value);
            }
        }
        return $data;
    }
    //高级查询
    
    private function lists($OrgID, $Objdata, $UserID, $data,$PageSize=10){
        //用来解析查询数据
        $where['where'] = isset($data['where']) ? $data['where'] : null; //高级查询条件
        $where['rel'] = isset($data['rel']) ? $data['rel'] : null; //高级查询公式
        if (empty($where['where']) && empty($where['rel'])) {
            $where = null;
        }
        
        $CurrentPage = 1;
        
        extract($Objdata, EXTR_OVERWRITE); //释放数组元素
        //查询列表数据
        $lead_attr = $this->attr->object_attr($OrgID, $OBJ_NAME);
        foreach ($lead_attr as $key => $value) {
            if($value['is_ref_obj']==0 && empty($value['fld_name'])){
            }elseif($key==$KEY_ATTR_NAME){
            }else{
                $list_format[] = $key;
            }
            
        }
        $list_data = $this->objects->GetInfoList($list_format, $lead_attr, $Objdata, $OrgID, $CurrentPage, $PageSize, $where,$UserID);
        
        $list_data = $this->JsonToArray($list_data);
        //p($list_data);die;
        if(!isset($list_data['rows'])){
            p($this->api_error('303'));
            return $this->api_error('303');//没有查询到数据
        }
        foreach ($list_data['rows'] as $k => $v) {
            foreach ($v['cell'] as $kk => $vv) {
                $arr = explode('.', $kk);
                if(isset($arr[2]) && $arr[2]== '0'){
                    continue;
                }
                $f_data[$k][$kk] = $vv;
            }
        }
        
        p($f_data);
        return $this->api_succ("高级查询接口执行成功！",$f_data);
    }

    //新增
    public function add($OrgID,$Objdata,$data,$UserID) {
        extract($Objdata,EXTR_OVERWRITE);
        $object_attr=$this->attr->object_attr($OrgID, $OBJ_NAME);
        foreach ($object_attr as $k => $v) {
            if($v['is_must'] && !isset($data[$k])){
                $res['res'] = "fail";
                $res['error_code'] = "201";
                $res['msg'] = "缺少\"" . $k . "\"参数，保存失败！";
                echo "\"" . $k . "\"未填，保存失败！";
                return $this->api_error($res['error_code'],$res['msg']);
            }
        }
        $Userdata = $this->user->SelectOne($OrgID,$data[$OBJ_NAME.'.OwnerID']);
        if($OBJ_NAME=='Contact'){
            if($Userdata['DEPT_ID'] != $data[$OBJ_NAME.'.OwnerDeptID']){
                return $this->api_error('304');
            }
        }else{
            if($Userdata['DEPT_ID'] != $data[$OBJ_NAME.'.DepartmentID']){
                return $this->api_error('304');
            }
        }
        
		$r_data = $this->objects->_add($data, $OBJ_NAME,$OrgID,$UserID);
        if($r_data['res']=='fail'){
            return $this->api_error($r_data['error_code'],$data['msg']);
        }else{
            return $this->api_succ("",$r_data['id']);
        }
        die;
        //echo 111;

    }
    //更新
    public function update($OrgID,$Objdata,$data,$UserID) {
        extract($Objdata,EXTR_OVERWRITE);
        $object_attr = $this->attr->object_attr($OrgID, $OBJ_NAME); 
        if(!isset($data[$KEY_ATTR_NAME])){
            return api_error('202',"ID必须存在");
        }elseif(empty($data[$KEY_ATTR_NAME])){
            return api_error('203',"ID不能为空");
        }else{
            $where = array(
            $KEY_ATTR_FLD => $data[$KEY_ATTR_NAME],
            );
            unset($data[$KEY_ATTR_NAME]);
        }

        if(isset($data[$OBJ_NAME.'.OwnerID'])){
            $Userdata = $this->user->SelectOne($OrgID,$data[$OBJ_NAME.'.OwnerID']);
            if($OBJ_NAME=='Contact'){
                if($Userdata['DEPT_ID'] != $data[$OBJ_NAME.'.OwnerDeptID']){
                    return $this->api_error('304');
                }
            }else{
                if($Userdata['DEPT_ID'] != $data[$OBJ_NAME.'.DepartmentID']){
                    return $this->api_error("304");
                }
            }
        }
        
        
        $r_data = $this->objects->_update($data, $where, $object_attr, $OBJ_NAME,$OrgID=1,$UserID);
        //$this->history($data, $obj_id, $content, $object_attr, $module,$UserID)
        p($r_data);
        if($r_data['res']=='fail'){

            return $this->api_error($r_data['error_code'],$r_data['msg']);
        }else{
            return $this->api_succ();
        }
        die;
        //echo 111;

    }
    //删除
    public function del($OrgID,$Objdata,$data,$UserID) {
        
        extract($Objdata, EXTR_OVERWRITE);
        if(!isset($data[$KEY_ATTR_NAME])){
            return api_error('202',"ID必须存在");
        }elseif(empty($data[$KEY_ATTR_NAME])){
            return api_error('203',"ID不能为空");
        }else{
            $id = $data[$OBJ_NAME.".ID"];
        }
        
        $d_data['DELETED_USER_ID'] = $UserID;
        $d_data['IS_DELETED'] = '1';
        $d_data['DELETED_TIME'] = "to_date('" . dataReduce8("Y-m-d H:i:s", date('Y-m-d H:i:s')) . "','yyyy-mm-dd hh24:mi:ss')";
        $pwhere = $this->objects->premissionList($Objdata, $OrgID, $d_data['DELETED_USER_ID'], "delete");
        if ($this->objects->_del($id, $OBJ_TYPE, $d_data, $pwhere)) {
            return $this->api_succ();
        } else {
            return $this->api_error('200',"");
        }
        
        //echo 111;

    }

    /**
     * 接口成功返回结果
     * $data 返回数据
     * $msg 消息内容
     */
    public function api_succ($msg="",$data=""){
        $res_data["res"]="succ";
        if($data!=""){
            $res_data["data"]=$data;
        }
        if($msg==""){
            $res_data["msg"]="接口执行成功";
        }else{
            $res_data["msg"]=$msg;
        }
        return json_encode($res_data);
    }

    /**
     * 失败返回结果
     * $error_code 失败类型
     * $msg 中文结果
     */
    public function api_error($error_code,$msg=""){
        $error_arr= array(
            "101" => "接口类型不能为空!",
            "102" => "未知类型!",
            "103" => "用户不存在!",
            "104" => "单位简称不存在!",
            "105" => "密码错误!",
            "106" => "请求对象不存在!",
            "200" => "该用户没有权限!",
            "201" => "创建参数不能为空。",
            "202" => "必填参数必须存在。",
            "203" => "必填参数不能为空。",
            "204" => "必须是数值。",
            "301" => "没有这个工号的所属销售。",
            "302" => "这个工号查询出多个销售。",
            "303" => "没有查询到数据。",
            "304" => "人员和部门不匹配!",
            "404" => "错误404",
        );

        $data["res"]="fail";
        $data["error_code"]=$error_code;
        if($msg==""){
            $data["msg"]=$error_arr[$error_code];
        }else{
            $data["msg"]=$msg;
        }
        return json_encode($data);
    }

 }

?>
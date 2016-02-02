<?php

class User_model extends CI_Model {

    private $user_data; //查询到的客户信息
    private $login_sys; //登录系统模块

    

    public function LoginUserData($LoginName,$OrgName){
        $this->load->model('admin/org_model', 'org');
        $OrgID = $this->org->NameGetId($OrgName);
        $where = array(
            'login_name' => $LoginName,
            'org_id' => $OrgID
            );
        $this->db->where($where);
        $this->db->from('tc_user');
        $data=$this->db->get()->result_array();
        return $data;
    }

    public function LoginUserData2($LoginName,$OrgID){
        $this->load->model('admin/org_model', 'org');
        $where = array(
            'login_name' => $LoginName,
            'org_id' => $OrgID
            );
        $this->db->where($where);
        $this->db->from('tc_user');
        $data=$this->db->get()->result_array();
        return $data;
    }

    /**
     * 查询这个用户名称是否存在
     */
    public function LoginNameIsExist($login_name, $org = "") {
        $info['where']["login_name"] = $login_name;
        if ($org == "") {
            $where["org_id"] = 1;
        } else {
            $this->load->model('admin/org_model', 'org');
            $this->org->NameGetId($org);
        }
        $data = $this->countGetInfo($info);
    }

    /**
     * 将字符串进行密码方式编码 
     * @param string $pswd 要加密编码的字符串 
     * @return string 加密的字符串 
     */
    public function encodePassword($pswd) {
        if ($pswd != "") {
            $encrpted = md5($pswd);
        } else {
            $encrpted = "";
        }
        return $encrpted;
    }

    /**
     * 比较源密码与加密的密码是否匹配 
     * @param string $pswd 源密码 
     * @param string $encrpted 加密的密码 
     * @return 如果匹配，返回0,否则返回1 
     */
    public function checkPassword($pswd, $encrpted) {
        if ($this->encodePassword($pswd) == $encrpted) {
            return true;
        } else {
            return flush;
        }
    }

    /**
     * 登陆验证
     * @param string $login_sys  模块编号
     * @param string $loginname  用户名
     * @param string $password   密码
     * @param string $orgcode	 单位简称（可以不传，不传时，自动去org_id 1）
     */
    public function CheckLogin($login_sys, $loginname, $password, $orgcode = "") {
        $this->load->model('admin/org_model', 'org');
        $orgID = 1;
        $ErrNo = $this->CheckUser($loginname, $password, true, $orgID, $login_sys);
        if ($ErrNo) {
            //echo "失败";exit;
            $errmsg = $this->getErrorMessage($ErrNo);
            return $errmsg;
            //exit;
        } else {
            //登陆成功
            $data = $this->user_data;
            //echo "登陆成功";exit;
            $this->session->set_userdata('lang_id', 2); //先默认为2,方便以后扩展
            $this->session->set_userdata('org_id', $orgID);
            $this->session->set_userdata('login_sys', $login_sys);
            $this->session->set_userdata('user_id', $data['user_id']);
            $this->session->set_userdata('user_name', $data['user_name']);
            $this->session->set_userdata('login_name', $data['user_login_name']);
            $this->session->set_userdata('is_admin', $is_admin);
            $this->session->set_userdata('time', time());
            //$this->user_data = null;
            //p($this->session->all_userdata());
            switch ($this->login_sys) {
                case 20000:
                    header('Location: ' . site_url() . "/admin");
                    break;
                case 1:
                    header('Location: ' . site_url() . "/www");
                    break;
                case 2:
                    header('Location: ' . site_url() . "/www");
                    break;
                default:
                    echo "未知模块";
                    exit;
                    break;
            }
        }
    }

    /**
     * 检查员工是否可以登录
     * @param string $login_name: 登录名称 
     * @param string $login_password: 登录密码 
     * @param bool $checkPass: 是否验证密码 
     * @param string $org_id: 账套号ID
     * @param string $login_sys: 登陆系统,支持的选项为: 20000: 后台系统配置(等价于"KEY"字母标识) 1: 前台营销管理(等价于只有营销许可时的"CRM"字母标识) 2: 服务管理(等价于只有服务许可时的"CRM"字母标识) 3: 营销服务管理(等价于有营销服务许可时的"CRM"字母标识) 20001: 决策分析(等价于"DSS"字母标识) 
     * @return string 返回错误号: -10001: 用户名空 -10002: 用户没有找到 -10003: 超过偿试次数, 用户被锁定 -10004: 密码错误 -10005: 用户已经停用 -100051: 没有权限登录该模块 
     */
    public function CheckUser($login_name = "", $login_password = "", $checkPass = true, $org_id, $login_sys) {
        if ($login_name == "") {
            return -10001;
        }
        //首先查询这个客户的信息
        $info['where']['user_login_name'] = $login_name;
        $data = $this->GetInfo($info);
        $this->user_data = $data;
        $this->login_sys = $login_sys;
        if (!$data) {
            return -10002;
        }
        if (md5($data["user_password"]) != $this->encodePassword($login_password)) {
            return -10004;
        }
        if ($data["STOP_FLAG"] == 1) {
            return -10005;
        }
    }

    /**
     * 获得错误信息
     */
    public function getErrorMessage($ErrNo) {
        switch ($ErrNo) {
            case -10000:
                $errmsg = "10000";
                break;
            case -10001:
                $errmsg = "用户名空";
                break;
            case -10002:
                $errmsg = "用户没有找到";
                break;
            case -10003:
                $errmsg = "超过偿试次数, 用户被锁定";
                break;
            case -10004:
                $errmsg = "密码错误";
                break;
            case -10005:
                $errmsg = "用户已经停用";
                break;
            default:
                $errmsg = "未知错误";
                break;
        }
        return $errmsg;
    }

    /**
     * 所有的查询都写一个通用方法
     */
    public function GetInfo($info) {
        //首先查询出本身所有的内容
        $this->db->from("tc_user");
        extract($info);
        if (!empty($select)) {
            $this->db->select($select);
        }
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($limit)) {
            $this->db->limit($limit[0], $limit[1]);
        }
        if (!empty($like)) {
            $this->db->like($like);
        }
        $data = $this->db->get()->result_array();
        if (count($data) == 1) {
            $data = $data[0];
        }
        return $data;
    }

    /**
     * 根据条件查询出数据总数
     * $where //判断条件
     */
    public function countGetInfo($info) {
        $this->db->from('tc_user');
        extract($info);
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($like)) {
            $this->db->like($like);
        }
        $count = $this->db->count_all_results();
        return $count;
    }

    /*
      $page //获得当前的页面值
      $perNumber //每页显示的记录数
     */

    public function listGetInfo($where, $page = 1, $perNumber = 10, $like = "") {
        $limitStart = ($page - 1) * $perNumber + 1 - 1; //这里因为数据库是从0开始算的！所以要减1
        $limit = array($perNumber, $limitStart);
        //p($limit);
        $data = $this->GetInfo($where, $limit, $like);
        return $data;
    }

    //用ID单个查询
    public function id_aGetInfo($id) {
        $where = array(
            'user_id' => $id,
        );
        $data = $this->GetInfo($where);
        $data = $data[0];
        return $data;
    }

    //单个修改
    public function update($data, $id) {
        $this->db->where('user_id', $id)->update('tc_user', $data);
    }

    //单个删除
    public function del($id) {
        $this->db->where('user_id', $id)->delete('tc_user');
    }

    //单个新增
    public function add($data) {
        $this->db->insert('tc_user', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function user_auth($id) {
        //echo "id:".$id;die;
        $role_info = $this->db->get_where('tc_role_user', array('user_id' => $id))->result_array();
        //p($role_info);
        $menu_auth_arr = array();
        $activity_auth_arr = array();
        $data_auth_arr = array();
        //p($role_info);
        //die;
        if (!empty($role_info)) {
            foreach ($role_info as $arr) {

                $tem = $this->db->select('role_id,role_menu_auth,role_activity_auth,role_data_auth')->from('tc_role')->where('role_id', $arr['role_id'])->get()->result_array();
                //p($arr);
                $auth_arr[] = $tem[0];
            }
            $user_auth = $auth_arr;
            //p($user_auth);
            if (!empty($user_auth)) {

                foreach ($user_auth as $arr) {
                    $menu_auth_arr = bingji($menu_auth_arr, json_decode($arr['role_menu_auth']));
                }
            }


            if (!empty($user_auth)) {
                foreach ($user_auth as $arr) {
                    //p($user_auth);
                    //p(object_array(json_decode($arr['role_activity_auth'])));
                    //p(json_decode($arr['role_activity_auth']));
                    $activity_auth_arr = bingji($activity_auth_arr, json_decode($arr['role_activity_auth']));
                }
            }



            if (!empty($user_auth)) {
                //p($user_auth);
                foreach ($user_auth as $arr) {
                    $arr['role_data_auth'] = json_decode($arr['role_data_auth']);
                    //p($arr['role_data_auth']);
                    $data_auth_arr = bingji($data_auth_arr, $arr['role_data_auth']);
                }
            }
        }


        $data['menu_auth_arr'] = $menu_auth_arr;
        $data['activity_auth_arr'] = $activity_auth_arr;
        $data['data_auth_arr'] = $data_auth_arr;
        //p($data['data_auth_arr']);
        return $data;
    }

    public function roleGetInfo($id, $like = "") {
        //p($like);
        //将like转换成判断条件加上去
        $like_str = "";
        if ($like != "") {
            foreach ($like as $k => $v) {
                $like_str .= 'and ' . $k . " like '%" . $v . "%'";
            }
        }
        $sql = "select * from tc_user where user_id in(SELECT user_id FROM `tc_role_user` WHERE role_id = " . $id . ") " . $like_str;
        $data = $this->db->query($sql)->result_array();
        foreach ($data as $k => $v) {
            //p($v);die;
            //所有属性循环
            //当属性类型为单选时
            if ($v['user_sex'] != "" and $v['user_sex'] != 0) {
                $this->load->model('admin/enum_model', 'enum');

                $data[$k]['user_sex_arr'] = $this->enum->getlist(64, $data[$k]['user_sex']);
            }

            if ($v['user_department'] != "" and $v['user_department'] != 0) {
                $this->load->model('www/department_model', 'department');
                $data[$k]['user_department_arr'] = $this->department->id_aGetInfo($v['user_department']);
            }

            //当属性类型为下拉单选时
            if ($v['user_duty_name'] != "" and $v['user_duty_name'] != 0) {
                $this->load->model('admin/enum_model', 'enum');

                $data[$k]['user_duty_name_arr'] = $this->enum->getlist(69, $data[$k]['user_duty_name']);
            }
            //当属性类型为单选时
            if ($v['user_status'] != "" and $v['user_status'] != 0) {
                $this->load->model('admin/enum_model', 'enum');

                $data[$k]['user_status_arr'] = $this->enum->getlist(70, $data[$k]['user_status']);
            }
        }
        return $data;
    }

    //该角色数据总数
    public function countRole($id, $like = "") {
        //将like转换成判断条件加上去
        $like_str = "";
        if ($like != "") {
            foreach ($like as $k => $v) {
                $like_str .= 'and ' . $k . " like '%" . $v . "%'";
            }
        }
        $sql = "select count(*) lee_count from tc_user where user_id in(SELECT user_id FROM `tc_role_user` WHERE role_id = " . $id . ") " . $like_str;
        $data = $this->db->query($sql)->result_array();
        $count = $data[0]['lee_count'];
        return $count;
    }

    /**
     * 	SelectOne					单一查询
     * 	@params	int	$OrgID	查询条件
     * 	@params	int	$UserId	查询条件
     * 	@return array				返回查询的结果集(限制一条)
     */
    public function SelectOne($OrgID, $UserId) {
        $where = array(
            "user_id" => $UserId,
            "org_id" => $OrgID,
        );
        $select = array(
            "user_name",
            "user_id",
            "dept_id",
        );
        $this->db->select($select);
        $this->db->where($where);
        $this->db->from("tc_user");
        $data = $this->db->get()->result_array();
        $new_data["Name"] = $data[0]["USER_NAME"];
        $new_data["ID"] = $data[0]["USER_ID"];
        $new_data["Dept_ID"] = $data[0]["DEPT_ID"];
        return $new_data;
    }

    public function update_password($OrgID, $user_id, $login_password){
        $time = date("Y-m-d H:i:s");
        $where = array(
            'org_id' => $OrgID,
            'user_id' => $user_id
            );
        $update = array(
            'login_password' => $this->encodePassword($login_password),
            'MODIFY_USER_ID' => $user_id,
            );
        $this->db->set('MODIFY_TIME', "to_date('" . $time . "','yyyy-mm-dd hh24:mi:ss')", false);
        $this->db->update('tc_user',$update)->where($where);
    }

}

?>

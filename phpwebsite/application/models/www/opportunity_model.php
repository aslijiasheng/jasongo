<?php

class Opportunity_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model("admin/nextid_model", "nextid");
        $this->load->model("www/objects_model", "objects");
        $this->load->model("admin/attr_model", "attr");
    }




    /**
     * Opportunity_model::opportunity_module_add()
     * 新增商机阶段方法
     * @param mixed $next_id
     * @param mixed $module
     * @param integer $org_id
     * @return void
     */
    public function opportunity_module_add($next_id, $module, $org_id = 1) {
        $fld = "owner_user_id,optnty_type,to_char(START_DATE,'yyyy-mm-dd') START_DATE";
        $oppor_data = $this->selectOne($next_id, $fld);
        $optnty_type = $oppor_data['OPTNTY_TYPE']; //取出模型ID
        $start_date = $oppor_data['START_DATE']; //取出主商机的销售时间
        $optnty_tbl_where = array(
            "type_id" => $optnty_type,
            "org_id" => $org_id,
            "obj_id" => 0,
        );
        /**
         * 取模阶段 select * from tc_opportunity_stage where org_id = 1 and obj_id = 0 and type_id = 2056;
         */
        $oppor_stage_data = $this->selectWhere($optnty_tbl_where);
        unset($optnty_tbl_where);
		$stage_path = array();//流程判断
        //循环阶段模型数据
        foreach ($oppor_stage_data as $key => $value) {
            $stage_next_id = $this->nextid->NewID("tc_opportunity_stage"); //获取自增的ID值
            $stage_id = $value['STAGE_ID']; //备用
            if ($stage_next_id > 0) {//判断获取的数据是否成功
				if ($key == 0) { //回写给主表
					$data['OPTNTY_STAGE'] = $stage_next_id;
					$this->update($data, $next_id);
				}
				$stage_path[$value['STAGE_ID']] = $stage_next_id;
                $value['STAGE_ID'] = $stage_next_id;//自增ID值从NEXTID模型文件中取出的
                $value['OBJ_ID'] = $next_id;//传递过来的自增ID值
                $value['TYPE_ID'] = 0;
                $this->doinsert($value); //更新数据后插入阶段表
                if(!empty($start_date)){
                    //插入商机阶段时间
                    $this->db->set('PLAN_START_TIME', "to_date('" . $start_date . "','yyyy-mm-dd')", false);
                    $start_date = date_add_sub($start_date);
                    $this->db->set('PLAN_END_TIME', "to_date('" . $start_date . "','yyyy-mm-dd')", false);
                    if($key==0) $this->db->set('START_TIME', "to_date('" . dataReduce8('Y-m-d H:i:s',date('Y-m-d H:i:s')) . "','yyyy-mm-dd hh24:mi:ss')", false);
                    $w = array(
                        "STAGE_ID" => $stage_next_id,
                    );
                    $this->db->where($w);
                    $this->db->update('tc_opportunity_stage');
                    //插入商机阶段时间
                }
                //取模 国际化阶段 插入 select * from tc_opportunity_stage_lang where stage_id = 2899;
                $tbl_where = array(
                    "stage_id" => $stage_id,
                );
                $stage_data = $this->selectWhere($tbl_where, "tc_opportunity_stage_lang"); //获取出针对stage_id得到的国际化字段信息
                if(!empty($stage_data)) {
                    foreach ($stage_data as $k => $v) {//循环插入
                        $v['STAGE_ID'] = $stage_next_id;//上一步插入后的自增ID值
                        $this->doinsert($v, "tc_opportunity_stage_lang");
                    }
                }
                unset($tbl_where);
                $uinsert = array('org_id'=>$org_id,'user_id'=>$oppor_data['OWNER_USER_ID'],'optnty_id'=>$next_id,'stage_id'=>$stage_id,'is_self'=>1);
                $this->db->insert('rel_opportunity_stage_user',$uinsert);
            }
        }

        //循环阶段模型数据 结束
        /**
         * 取模流程 select * from tc_stage_path  where obj_id = 0 and type_id = 2056;
         */
        $optnty_tbl_where = array(
            "obj_id" => 0,
            "type_id" => $optnty_type,
            "org_id" => $org_id

        );
        $stage_path_data = $this->selectWhere($optnty_tbl_where, "tc_stage_path"); //获取模型数据 关于阶段流程的

        unset($optnty_tbl_where);
        foreach ($stage_path_data as $key => $value) {
            $stage_path_next_id = $this->nextid->NewID("tc_stage_path"); //获取自增的ID值
            $path_id = $value['PATH_ID']; //备用
            if ($stage_path_next_id > 0) {//判断获取的数据是否成功
                $value['PATH_ID'] = $stage_path_next_id;//自增ID值从NEXTID模型文件中取出的
                $value['OBJ_ID'] = $next_id;//传递过来的自增ID值
				$value['OLD_STAGE'] = $stage_path[$value['OLD_STAGE']];//开始阶段
				$value['NEW_STAGE'] = $stage_path[$value['NEW_STAGE']];//结束阶段
                $value['TYPE_ID'] = 0;
                $this->doinsert($value, "tc_stage_path"); //更新数据后插入阶段流程表
                $tbl_where = array(
                    "path_id" => $path_id,
                );
                $stage_path_action_data = $this->selectWhere($tbl_where, "tc_stage_path_action"); //获取出针对$path_id得到的相关行动信息
                if(!empty($stage_path_action_data)){
                    foreach ($stage_path_action_data as $k => $v) {//循环插入
                        $v['PATH_ID'] = $stage_path_next_id;//上一步插入后的自增ID值
                        $this->doinsert($v, "tc_stage_path_action");
                    }
                }
                unset($tbl_where);
                
            }
        }
    }

    /**
     * 单一查询
     * @param int $id 主键ID
     * @param string $tbl_name 表名
     * @return type 返回结果集
     */
    public function selectOne($id, $fld = null, $tbl_name = "tc_opportunity") {
        $this->db->from($tbl_name);
        $this->db->where("optnty_id", $id);
        if (!empty($fld)) {
           $this->db->select($fld); 
        }
        $data = $this->db->get()->result_array();
        return $data[0];
    }
	
	//单个修改
    public function update($data, $id) {
        $this->db->where('optnty_id', $id)->update('tc_opportunity', $data);
    }

    /**
     * 根据条件查询
     */
    public function selectWhere($tbl_where, $tbl_name = "tc_opportunity_stage") {
        $this->db->from($tbl_name);
        $this->db->where($tbl_where);
        $data = $this->db->get()->result_array();
        return $data;
    }

    /**
     * 根据数据新增一条数据
     */
    public function doinsert($tbl_data, $tbl_name = "tc_opportunity_stage") {
        $this->db->insert($tbl_name, $tbl_data);
    }

    /**
     * 获取阶段申迁的的new_stage
     * @param $id 商机id
     */
    public function get_opportunity_new_stage($id){
        $select = array(
            "optnty_stage"
        );
        $condition = array(
            'org_id' => $this->session->userdata('org_id'),
            "optnty_id" => $id
        );
        $query = $this->db -> select($select)->where($condition)->get("tc_opportunity")->result_array();
        $phase_id = $query[0]["OPTNTY_STAGE"];
        $select = array(
            'a.new_stage',
            'b.data_value'
        );
        $condition = array(
            'a.obj_id' => $id,
            'a.org_id' => $this->session->userdata('org_id'),
            'b.lang_id' => 2,
            "a.old_stage" => $phase_id

        );
        $res = $this->db->from("tc_stage_path a")->
            join('tc_opportunity_stage_lang b', 'a.new_stage = b.stage_id', 'left')->
            where($condition)->
            select($select)->get()->result_array();

        if(count($res)) {
            return $res;
        }else{
            return false;
        }
    }

    /**
     * 查询出来客户的id
     * @param $id  商机id
     * @return mixed 客户的id
     */
    public function get_account_id($id){
        $select = "account_id";
        $condition = array(
            'org_id' => $this->session->userdata('org_id'),
            "optnty_id" => $id
        );
        $query = $this->db -> select($select)->where($condition)->get("tc_opportunity")->result_array();
        return $query[0][strtoupper($select)];
    }

    /**
     * 得到客户类型
     * @param $account_id 客户id
     * @return bool
     */
    public function get_account_emstatus($account_id){
        $select = "acct_int39";
        $condition = array(
            'org_id' => $this->session->userdata('org_id'),
            "account_id" => $account_id
        );
        $query = $this->db -> select($select)->where($condition)->get("tc_account_attr")->result_array();
        if($query[0][strtoupper($select)] == 1001){
          return true;
        }else{
          return false;
        }

    }

    /**
     * 查询出来客户的邮箱和电话号码
     * @param $account_id  客户的id
     * @return mixed
     */
    public function get_account_info($account_id){
        $select = array(
            "account_email",
            "account_mobile_phone",
            "account_type",
            "account_name",
            "acct_int04"
        );
        $condition = array(
            'org_id' => $this->session->userdata('org_id'),
            "account_id" => $account_id
        );
        $query = $this->db -> select($select)->where($condition)->get("tc_account")->result_array();
        return $query[0];
    }

    /**
     * 从接口的到的商业id是不是存在
     * @param $business_id  商业id
     * @return mixed 商业id的条数
     */
    public function rechecking_id($business_id){
        $condition = array(
            'org_id' => $this->session->userdata('org_id'),
            "acct_char01" => $business_id
        );
        $res  = $this->db ->where($condition)->count_all_results("tc_account");
        return $res;

    }

    /**
     * 修改用户的商业id和用户的类型为签约客户
     *
     */
    public  function  update_account_business_type($data){
        return $this -> objects -> init($data);
    }


    //opportunity保存方法
    public function opportunity_save($data){
        return  $this -> opportunity_phase_save($data);
    }

    private function opportunity_phase_save($data){
        return $this -> objects -> init($data);
    }

}

?>
<?php
class Account_model extends CI_Model{

    public function __construct() {
        parent :: __construct();
    }

    /**
     * 	SelectOne					单一查询
     * 	@params	int	$OrgID	查询条件
     * 	@params	int	$DeptId	查询条件
     * 	@return array				返回查询的结果集(限制一条)
     */
    public function SelectOne($OrgID, $DeptId) {
        $where = array(
            "account_id" => $DeptId,
            "org_id" => $OrgID,
        );
        $select = array(
            "account_name",
            "account_id",
        );
        $this->db->select($select);
        $this->db->where($where);
        $this->db->from("tc_account");
        $data = $this->db->get()->result_array();
        $new_data["Name"] = $data[0]["ACCOUNT_NAME"];
        $new_data["ID"] = $data[0]["ACCOUNT_ID"];
        return $new_data;
    }

    public function account_is_unique(&$data){
        if($data['tc_account']['Account.TypeID']==2057 || $data['tc_account']['Account.TypeID']==26){
            $where = array(
                'org_id' => 1,
                'account_name' => $data['tc_account']['Account.Name'],
                );
            $res = $this->db->from('tc_account')->where($where)->get()->result_array();
            if(count($res)>0){
                
                echo '{"res":"fail","msg":"保存失败！客户名称重复，数据库中已存在！"}';die;
            }
        }
    }
}
?>


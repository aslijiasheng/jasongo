<?php

/**
 * Objects_model
 * 公用业务处理层
 * @package ncrm
 * @author shopex
 * @copyright 2014
 * @version 1.0
 * @access public
 */
class Objects_model extends CI_Model {

    private $RefType = '18'; //引用类型改18
    private $RefUrl = "";
    private $DialogWidth = 800;

    /**
     * Objects_model::__construct()
     * 构造方法
     * @return void
     */
    public function __construct() {
        parent :: __construct();
        $this->load->model("admin/obj_model", "obj");
        $this->load->model("admin/enum_model", "enum");
        $this->load->model("admin/nextid_model", "nextid");

        $this->load->model('www/user_model','user');
        $this->load->model('www/department_model','dept');
        $this->load->model("admin/type_model", "type");
        $this->load->model("www/remind_model","remind");

        $this->RefUrl = $this->config->base_url() . $this->config->item(@index_page) . "/www/objects/ajax_lists?obj_type=";
    }

    /**
     * @param $ListFormat  需要查询的字段属性
     * @param $obj_data   对象的信息
     * @return mixed   加入属性后的需要查询的新的属性数组
     */
    public function add_attribute($ListFormat,$obj_data){
      if($obj_data["OBJ_TYPE"] == 1 || $obj_data["OBJ_TYPE"] == 3) {
          array_push($ListFormat,$obj_data["OBJ_NAME"].".StopFlag");
      }
      return $ListFormat;


    }
    /**
     * GetInfoList 查询列表页面数据
     * @param array $ListFormat 列表布局
     * @param array $LeadAttr 线索的所有属性信息
     * @param array $obj_data 主对象相关的资料
     * @param int $OrgID 组织ID
     * @param int $CurrentPage 当前页数
     * @param int $PageSize 每页多少条数据
     * @param array $Where 判断条件
     * @return json 返回前台调用的json数组
     */
    public function GetInfoList($ListFormat, $LeadAttr, $obj_data, $OrgID, $user_id, $CurrentPage = 1, $PageSize = 10, $where = array(), $user_id = "") {
        $for_data = null;
        $ListFormat = $this -> add_attribute($ListFormat,$obj_data);
        if (!empty($obj_data['DTL_OBJ_NAME'])) { //明细对象为空证明只有方对象那么判断是否有双.
            $attrs_format = $this->is_detail_format($ListFormat, $obj_data, $OrgID);
        } else {
            $for_data = $this->parse_formart($ListFormat); //取出引用的字段
        }
        sort($ListFormat);
        if (!empty($attrs_format)) {
            $LeadAttr['rel_condition'] = $attrs_format;
        }
        $info_in = null;
        if ($for_data) {
            $data_format = $for_data['new_data'];
            $history_data = $for_data['history_data'];
            $obj_name = current($for_data);
            $data_format_leadAttr = $this->attr->object_attr(1, $obj_name);
            $data_format_obj_data = $this->obj->NameGetObj(1, $obj_name);
            $res_data = $this->InfoPagerList($ListFormat, $LeadAttr, $obj_data, $OrgID, $user_id, $CurrentPage, $PageSize, $where, $data_format_obj_data, null, $for_data, null, true);
            $info_in = $this->InfoIn($res_data, $data_format_obj_data, $OrgID);
            return $this->InfoPagerList($data_format, $data_format_leadAttr, $data_format_obj_data, $OrgID, $user_id, $CurrentPage, $PageSize, $where, $data_format_obj_data, $res_data, $for_data, $info_in);
        }
        return $this->InfoPagerList($ListFormat, $LeadAttr, $obj_data, $OrgID, $user_id, $CurrentPage, $PageSize, $where);
    }

    /**
     * 判断是否有明细对象并解析
     */
    protected function is_detail_format(&$list_format, $obj_data, $org_id) {
        $res_data = array();
        extract($obj_data, EXTR_OVERWRITE);
        //多.关联属性与主对象关联方式
        /*
         * select * from dd_object_option where org_id = 1 and obj_name like '次级对象'
         * select * from dd_attribute where attr_name = '从次级对象中查出的partattr值' and org_id =1
         * 查出后再找出referred_by再从referred_by中找出fld_name
         */
        foreach ($list_format as $k => &$v) {
			$history_fld = $v;
            $attrValues = ConverArray($v, ".");
            if (count($attrValues) > 2) {
				$attr_name = $attrValues[0] . "." . $attrValues[1];
				$attr_sql = "select * from dd_attribute where attr_name = '$attr_name' and org_id = $org_id";
                $str_obj = $this->db->query($attr_sql)->result_array();
				$str_obj = $str_obj[0];
				$str_obj_name = $str_obj['REF_OBJ_NAME'];
                $str_w = array(//拼接条件
                    "org_id" => $org_id,
                    "obj_name" => $str_obj_name,
                );
                //第一次关联条件开始
                //通过查询出来的refer_obj_name对象查询出引用的对象信息用来做关联
                $this->db->select("MAIN_TABLE,KEY_ATTR_FLD");
                $this->db->from("dd_object");
                $this->db->where($str_w);
                $ref_obj_data = $this->db->get()->result_array();
                $ref_obj_data = $ref_obj_data[0];
                //再通过查询出来的referred_by字段进行查找FLD_NAME使得刚查出来的主键ID与FLD_NAME进行关联
                $ref_cond_w = array(
                    "org_id" => $org_id,
                    "attr_name" => $str_obj['REFERRED_BY'],
                );
                $this->db->select("TBL_NAME,FLD_NAME");
                $this->db->from("dd_attribute");
                $this->db->where($ref_cond_w);
                $ref_cond_data = $this->db->get()->result_array();
                $ref_cond_data = $ref_cond_data[0];
                //第一次关联条件完成
                $cond_ref = $ref_obj_data['MAIN_TABLE'] . "." . $ref_obj_data['KEY_ATTR_FLD'] . " = " . $ref_cond_data['TBL_NAME'] . "." . $ref_cond_data['FLD_NAME'];
                //查出次级对象的属性ID
                $this->db->select("PARENT_ATTR");
                $this->db->from("dd_object_option");
                $this->db->where($str_w);
                $obj_option = $this->db->get()->result_array();
                $obj_option = $obj_option[0];
                //从次级对象中查出的ID值进行查找关联数据
                $attr_bean['where']['attr_name'] = $obj_option['PARENT_ATTR'];
                $attr_bean['where']['org_id'] = $org_id;
                $attrs = $this->attr->SelectOne($attr_bean);
                unset($attr_bean);
                $attr_bean['where']['attr_name'] = $attrs['REFERRED_BY'];
                $attr_bean['where']['org_id'] = $org_id;
                $attrs_tbl_relfld = $this->attr->SelectOne($attr_bean);
                unset($attr_bean);
                $attr_bean['where']['attr_name'] = $str_obj_name . "." . $attrValues[2];
                $attr_bean['where']['org_id'] = $org_id;
                $attrs_fld = $this->attr->SelectOne($attr_bean);
				$list_format[$k] = $attrs_fld['ATTR_NAME'];
                $res_data[] = array(
                    "attrs_tbl_refld" => $attrs_tbl_relfld, //表和关联字段
                    "attrs_fld" => $attrs_fld['FLD_NAME'], //待查询字段
                    "history_fld" => $history_fld, //旧属性
                    "new_fld" => $attrs_fld['ATTR_NAME'], //新属性
                    "cond_ref" => $cond_ref,//额外的关联条件
                );
            }
        }
        return $res_data;
    }
	
	/**
	 * 分页查询总冈方法
	 */
    protected function InfoPagerList($ListFormat, $LeadAttr, $obj_data, $OrgID, $user_id, $CurrentPage = 1, $PageSize = 10, $where = array(), $data_format_obj_data = null, $res_data = null, $for_data = null, $info_in = null, $is_bool = false) {
        $this->queryisnotformat($ListFormat, $LeadAttr);
        $first_tbl = $obj_data["MAIN_TABLE"]; //主表名称
        if (isset($LeadAttr['rel_condition'])) {
            $rel_condition = $LeadAttr['rel_condition'];
            $rel_cond = array();
            unset($LeadAttr['rel_condition']);
            foreach ($rel_condition as $k => $v) {
                $rel_cond[$v['attrs_tbl_refld']['TBL_NAME']] = $v['attrs_tbl_refld']['FLD_NAME'];
                $rel_cond['cond_ref'] = $v['cond_ref'];
                $rel_cond_obj_name = $v['attrs_tbl_refld']['OBJ_NAME'];
                $rel_res_data = $this->attr->object_attr($OrgID, $rel_cond_obj_name);
                $LeadAttr = array_merge($LeadAttr, $rel_res_data);
            }
        }
        $tbl_data = $this->GetTbl($ListFormat, $LeadAttr); //获取表名
        $fld_data = $this->GetFld($ListFormat, $LeadAttr); //获取字段名
        array_push($fld_data, $first_tbl . "." . ucfirst($obj_data["KEY_ATTR_FLD"]));
        if (!empty($data_format_obj_data)) {
            $AttrBean['select'] = "*";
            $AttrBean['where']["FLD_NAME"] = $data_format_obj_data["KEY_ATTR_FLD"];
            $AttrBean['where']['org_id'] = $OrgID;
            $attr_data = $this->attr->SelectOne($AttrBean);
            if ($is_bool) {
                array_push($fld_data, $first_tbl . "." . ucfirst($data_format_obj_data["KEY_ATTR_FLD"]));
            }
        }
        //判断查询是否有过滤条件
        if (!empty($where)) {
            $this->parase_tbl($tbl_data, $LeadAttr, $where);
            $w = $this->parse_w($OrgID, $where);
            $this->db->where($w, NULL, FALSE);
        }
        if (!empty($info_in)) {
            $w_in = $first_tbl . "." . $obj_data["KEY_ATTR_FLD"] . " in ( " . $info_in . ")";
            $this->db->where($w_in, null, false);
        }
        $this->db->select($fld_data);
        $count_tbl = count($tbl_data); //获取得到的表数目
        //如果只有一张表无需使用join
        if ($count_tbl == 1) {
            $this->db->from(current($tbl_data));
        } else {
            $this->db->from($first_tbl);
            if (isset($rel_cond)) {
                $cond_ref = $rel_cond['cond_ref'];
                unset($rel_cond['cond_ref']);
                foreach ($rel_cond as $k => $v) {
                    $join_on = "$first_tbl." . $obj_data["KEY_ATTR_FLD"] . " = $k." . $v;
                    $join_on .= " and  $cond_ref ";
                    $this->db->join($k, $join_on, "inner");
                }
            } else {
                sort($tbl_data);
                foreach ($tbl_data as $k => $v) {
                    $join_on = "$first_tbl." . $obj_data["KEY_ATTR_FLD"] . " = $v." . $obj_data["KEY_ATTR_FLD"] . " and $v.ORG_ID = $OrgID";
                    if ($v == $first_tbl) {
                        continue;
                    }
                    $this->db->join($v, $join_on, "inner");
                }
            }
        }
        /**
         * 拼接过滤条件 判断 表中主键是否大于0 是否为已删除数据  按创建时间 排序
         */
        $FilterPriKey = $first_tbl . "." . $obj_data["KEY_ATTR_FLD"] . ">=";
//$obj_data['IS_RECYCLABLE'] 是否放入回收站  如果是0则直接删除 如果是1则逻辑删除所以需在查询时做逻辑判断
        if ($obj_data['IS_RECYCLABLE']) {
            $IsDEleteKey = $first_tbl . "." . "is_deleted";
            $FilterWhere[$IsDEleteKey] = 0;
        }
        $FilterWhere[$FilterPriKey] = 1;
        $OrgIDKey = $first_tbl . "." . "org_id";
        $FilterWhere[$OrgIDKey] = 1;
        $this->db->where($FilterWhere);
        /**
         * 拼接数据权限
         */
        $w1 = $this->premissionList($obj_data, $OrgID, $user_id);
        $this->db->where($w1, NULL, FALSE);
        /**
         * 拼接数据权限结束
         */
        $this->db->order_by($first_tbl . "." . $obj_data["KEY_ATTR_FLD"], "desc");
        /**
         * 拼接过滤条件结束
         */
//开始分页
        $this->db->limit($PageSize, ($CurrentPage - 1) * $PageSize + 1);
        $data = $this->db->get()->result_array();
        //p($this->db->last_query());
//将ID主键压入返回的数组字段中
        array_push($ListFormat, $obj_data["KEY_ATTR_NAME"]);
        //if($obj_data["OBJ_TYPE"] ==1 || $obj_data["OBJ_TYPE"]==3 ){
        //    array_push($ListFormat, $obj_data["ATTR_PREFIX"].".StopFlag");
        //}
        if (isset($attr_data)) {
            array_push($ListFormat, $attr_data["ATTR_NAME"]);
        }

        if (!empty($data)) {

            $data = $this->FldConverName($data, $ListFormat, $LeadAttr);
            /*             * *开始解析数据类型*** */
            $data = $this->GetDataType($data, $LeadAttr, $OrgID);

			if (isset($rel_condition)) {
				$this->condMerger($data, $rel_condition);
			}
            if (!empty($res_data)) {
                $data = _HashData($data, $obj_data["KEY_ATTR_NAME"]);
                $this->InfoMerger($data, $res_data, $attr_data, $for_data);
            } else {
                $res_data = $data;
            }
        }
        if ($is_bool) {
            return $data;
        }
        $count = $this->InoPageCount($tbl_data, $rel_cond, $obj_data, $where, $w, $w1, null, $OrgID);
        return $this->InfoPagers($CurrentPage, $count, $PageSize, $res_data);
    }
    
    /**
     * Objects_model::parase_tbl()
     * 解析条件中的附加表名
     * @param mixed $tbl_data
     * @param mixed $LeadAttr
     * @param mixed $where
     * @return void
     */
    protected function parase_tbl( &$tbl_data, $LeadAttr, $where) {
        $w_attrs = $where['where'];
        foreach ($w_attrs as $k => $v) {
            if (isset($LeadAttr[$v['attr']]) && !in_array($LeadAttr[$v['attr']]['tbl_name'], $tbl_data)) {
                $tbl_data[] = $LeadAttr[$v['attr']]['tbl_name'];
            }
        }
    }

    /**
     * 新旧字段合并
     */
    protected function condMerger(&$data, $rel_condition) {
        $rel_data = _HashData($rel_condition, 'new_fld');
        foreach ($data as $k => $v) {
            foreach ($v as $rel_k => $rel_v) {
                if (isset($rel_data[$rel_k])) {
                    $data[$k][$rel_data[$rel_k]['history_fld']] = $rel_v;
                }
            }
        }
    }

    /**
     * GetFld						解析要查询的字段
     * @param	array	$data		要分析的数据
     * @param	array	$NeedAttr	需要的过滤的属性
     * @return	array				返回的数组
     */
    public function FldConverName($data, $NeedAttr, $LeadAttr) {
        foreach ($data as $k => $v) {
            $i = 0;
            foreach ($v as $kk => $vv) {
                if ($kk != 'RNUM') {
                    $newdata[$k][$NeedAttr[$i]] = $vv;
                } else {
                    //$newdata[$k]['RNUM'] = $vv;
                }
                $i++;
            }
        }
        /*
          foreach ($data as $k => $v) {
          unset($data[$k]['RNUM']);

          foreach ($NeedAttr as $kk => $vv) {
          if ($LeadAttr[$vv]['is_ref_obj']) {
          $fld_name = strtoupper($LeadAttr[$LeadAttr[$vv]['referred_by']]['fld_name']);
          $newdata[$k][$vv] = $data[$k][$fld_name];
          } else {
          $fld_name = strtoupper($LeadAttr[$vv]['fld_name']);
          $newdata[$k][$vv] = $data[$k][$fld_name];
          }
          }
          }
         */
        return $newdata;
    }

    /**
     * Objects_model::InfoMerger()
     * 数据合并 历史数据与实际数据
     * @param mixed $res_data
     * @param mixed $data
     * @param mixed $attr_data
     * @param mixed $for_data
     * @return void
     */
    protected function InfoMerger($res_data, &$data, $attr_data, $for_data) {
        foreach ($data as $key => &$value) {
            if (isset($res_data[$value[$attr_data["ATTR_NAME"]]])) {
                foreach ($for_data['history_data'] as $k => $v) {
                    $value[$v] = $res_data[$value[$attr_data["ATTR_NAME"]]][$for_data['new_data'][$k]];
                }
            }
        }
    }

    /**
     * Objects_model::InfoPagers()
     * 分页总纲
     * @param mixed $CurrentPage 当前页
     * @param mixed $count 总页数
     * @param mixed $PageSize 一页显示的数据
     * @param mixed $data 查询出的数据
     * @return json 分页数据
     */
    public function InfoPagers($CurrentPage, $count, $PageSize, $data) {
//要返回的JSON由 数据 当前页 总页数 数据总数 五部分组成
        $responce->page = $CurrentPage; //当前页
        $responce->records = $count;
        if ($count > 0) {
            $total_pages = ceil($count / $PageSize);
        } else {
            $total_pages = 0;
        }
        $responce->total = $total_pages; //分页总数
//循环取出分页后的列表JSON
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $responce->rows[$k]["cell"] = $v;
            }
        }
//总页数
        return json_encode($responce);
        die;
    }

    /**
     * Objects_model::InoPageCount()
     * 分页计数总纲
     * @param mixed $tbl_data 表名
     * @param mixed $rel_cond 条件
     * @param mixed $obj_data 主对象信息
     * @param mixed $where 条件
     * @param mixed $w 条件
     * @param mixed $w1 条件
     * @param mixed $w_in 至空 预留 
     * @param mixed $OrgID 标识ID
     * @return 分页总数
     */
    protected function InoPageCount($tbl_data, $rel_cond, $obj_data, $where, $w, $w1, $w_in, $OrgID) {
        /*         * *完成解析数据类型*** */
        $first_tbl = $obj_data['MAIN_TABLE'];
        $count_tbl = count($tbl_data);
        //如果只有一张表无需使用join
        if ($count_tbl == 1) {
            $this->db->from(current($tbl_data));
        } else {
            $this->db->from($first_tbl);
            if (isset($rel_cond)) {
                $cond_ref = $rel_cond['cond_ref'];
                unset($rel_cond['cond_ref']);
                foreach ($rel_cond as $k => $v) {
                    $join_on = "$first_tbl." . $obj_data["KEY_ATTR_FLD"] . " = $k." . $v;
                    $join_on .= " and  $cond_ref ";
                    $this->db->join($k, $join_on, "inner");
                }
            } else {
                sort($tbl_data);
                foreach ($tbl_data as $k => $v) {
                    $join_on = "$first_tbl." . $obj_data["KEY_ATTR_FLD"] . " = $v." . $obj_data["KEY_ATTR_FLD"] . " and $v.ORG_ID = $OrgID";
                    if ($v == $first_tbl) {
                        continue;
                    }
                    $this->db->join($v, $join_on, "inner");
                }
            }
        }
        if (!empty($where)) {
            $this->db->where($w, NULL, FALSE);
        }
        if (!empty($w_in)) {
            $this->db->where($w_in, NULL, FALSE);
        }
        /**
         * 拼接过滤条件 判断 表中主键是否大于0 是否为已删除数据  按创建时间 排序
         */
        $FilterPriKey = $first_tbl . "." . $obj_data["KEY_ATTR_FLD"] . ">=";
//$obj_data['IS_RECYCLABLE'] 是否放入回收站  如果是0则直接删除 如果是1则逻辑删除所以需在查询时做逻辑判断
        if ($obj_data['IS_RECYCLABLE']) {
            $IsDEleteKey = $first_tbl . "." . "is_deleted";
            $FilterWhere[$IsDEleteKey] = 0;
        }
        $FilterWhere[$FilterPriKey] = 1;
        $OrgIDKey = $first_tbl . "." . "org_id";
        $FilterWhere[$OrgIDKey] = 1;
        $this->db->where($FilterWhere);
        /**
         * 拼接数据权限
         */
        $this->db->where($w1, NULL, FALSE);
        /**
         * 拼接数据权限结束
         */
        $count = $this->db->count_all_results();
        return $count;
    }

    /**
     * Objects_model::InfoIn()
     * 拼接查询条件IN语句
     * @param mixed $res_data
     * @param mixed $data_format_obj_data
     * @param mixed $org_id
     * @return string IN条件语句
     */
    protected function InfoIn($res_data, $data_format_obj_data, $org_id) {
        $w = "";
//        $AttrBean['select'] = "*";
//        $AttrBean['where']["FLD_NAME"] = $data_format_obj_data["KEY_ATTR_FLD"];
//        $attr_data = $this->attr->SelectOne($AttrBean);
        $AttrBean['select'] = "*";
        $AttrBean['where']["FLD_NAME"] = $data_format_obj_data["KEY_ATTR_FLD"];
        $AttrBean['where']['org_id'] = $org_id;
        $attr_data = $this->attr->SelectOne($AttrBean);
        foreach ($res_data as $k => $v) {
            $w .= $v[$attr_data["ATTR_NAME"]] . ",";
        }
        return substr($w, 0, strlen($w) - 1);
    }

    /**
     * 相关对象列表
     * @param type $list_format 拉取出的要查询的数据
     * @param type $lead_attr 子对象属性信息
     * @param type $obj_data 子对象信息
     */
    public function GetInfoObjectList($list_format, $lead_attr, $obj_data, $org_id, $CurrentPage, $PageSize, $post_json, $condition) {
        $list_format = explode(";", $list_format);
        $this->queryisnotformat($list_format, $lead_attr);
        $list_format[] = $obj_data['KEY_ATTR_NAME'];//加入子对象主键
        $fld_data = $this->GetFld($list_format, $lead_attr); //获取字段名
        $this->list_object_fld($fld_data, $obj_data);
        $sql = $this->rel_parse_condition($condition, $post_json, $obj_data, $lead_attr, $org_id);
        $sql = "select " . implode(",", $fld_data) . $sql;
        $sql = $this->sql_parse($sql, $post_json);
        $data = $this->db->query($sql)->result_array();
        $r_data = $data;
        if (!empty($data)) {
            $data = $this->FldConverName($data, $list_format, $lead_attr);

            $this->Clist_object_data($r_data, $data, $obj_data);
            $data = $this->GetDataType($data, $lead_attr, $org_id);
        }
        $count = count($data);
        return $this->InfoPagers($CurrentPage, $count, $PageSize, $data);
    }
    
    /**
     * Objects_model::queryisnotformat()
     * 处理在查询时找不到的字段
     * @param mixed $list_format
     * @param mixed $attrs
     * @return void
     */
    protected function queryisnotformat(&$list_format, $attrs) {
        //查不到的字段
        foreach ($list_format as $k => $v) {
            if (empty($attrs[$v]['fld_name']) && empty($attrs[$v]['is_ref_obj'])) {
                unset($list_format[$k]);
            }
        }
        sort($list_format);
    }
    
    /**
     * 相关列表对象附加数据转换
     */ 
    protected function Clist_object_data( $r_data, &$data, $obj_data) {
        switch ($obj_data['OBJ_TYPE']) {
            case "99":
                $this->CObject_data($r_data, $data);
                break;
        }
    }
    
    /**
     * 相关员工数据转换
     */
    protected function CObject_data($r_data, &$data) {
        foreach ($data as $k => &$v) {
            $v['User.IsMaster'] = $r_data[$k]['IS_SELF'] == 1 ? "负责员工" : "相关员工";
        }
    }
    
    /**
     * 相关对象列表附加列处理
     */
    protected function list_object_fld( & $fld_data, $obj_data) {
        switch($obj_data['OBJ_TYPE']){
            case "99":
                $this->rel_user_object($fld_data);
                break;
        }
    }
    
    /**
     *  用户相关对象列表附加列处理
     */
    protected function rel_user_object( & $fld_data) {
        $fld_data[] = "is_self";
    }

    /**
     * 再次解析SQL语句
     */
    protected function sql_parse($sql, $post_json) {
        $post_tbl = $post_json[0]; //根拼接关联有关的数据
        $post_id = $post_json[1]; //根查询条件相关的值
        $post_tbl_primary = $post_tbl['OBJ_TYPE']; //主对象类型
        $main_object = $this->obj->getOneData($post_tbl_primary);
        $prefix_tbl = strtolower($main_object['OBJ_NAME']);
        if (!strpos($sql, "xxx")) {
            return $sql;
        }
        $sql = str_replace("xxx", $prefix_tbl, $sql);
        return $sql;
    }

    /**
     * 解析条件
     */
    protected function rel_parse_condition($condition, $post_json, $obj_data, $lead_attr, $org_id) {
        $sql = "";
        $post_tbl = $post_json[0]; //根拼接关联有关的数据
        $post_id = $post_json[1]; //根查询条件相关的值
        $post_tbl_primary = $post_tbl['OBJ_TYPE']; //主对象类型
        $post_cond_id = $post_tbl['COND_ID']; //最终条件
        $main_object = $this->obj->getOneData($post_tbl_primary);
        $main_attr = $this->attr->object_attr($org_id, $main_object['OBJ_NAME']);
        if (!empty($condition)) {
            foreach ($condition as $k => $v) {
                $select_tbl = " from $k $k ";
                foreach ($v as $kk => $vv) {
                    $sql .= " inner join $kk $kk on ";
                    foreach ($vv as $cond_k => $cond_v) {
                        $sql .= $cond_k . "=" . $cond_v;
                        $sql .= " and $kk.org_id = $org_id ";
                    }
                }
            }
            $sql = $select_tbl . $sql;
            $sql_cond = "select cond_attr,cond_value1,cond_value2,is_reference from tc_user_rel_condition where org_id=$org_id and cond_id = $post_cond_id";
            $cond_data = $this->db->query($sql_cond)->result_array();
            //解析出查询条件
            $cond_res = $this->rel_to_cond($cond_data, $main_attr, $lead_attr, $post_id);
            foreach ($cond_res as $k => $v) {
                $sql .= " where " . $main_object['MAIN_TABLE'] . ".$k = $v and";
            }
            $sql = substr($sql, 0, strrpos($sql, "and", -1));
            return $sql;
        } else {
            $sql_cond = "select cond_attr,cond_value1,cond_value2,is_reference from tc_user_rel_condition where org_id=$org_id and cond_id = $post_cond_id";
            $cond_data = $this->db->query($sql_cond)->result_array();
            //解析条件因为是没有LEE_CONDITION内容所以只是主副表条件关联
            $cond_res = $this->rel_to_emp_cond($cond_data, $main_object, $main_attr, $obj_data, $lead_attr, $post_id);
//            p($cond_res);
            //$cond_data, $main_obj, $main_attr, $rel_obj, $lead_attr, $post_id
            $sql = " from " . $main_object['MAIN_TABLE'] . " " . $main_object['MAIN_TABLE'];
            $sql .= " inner join " . $obj_data['MAIN_TABLE'] . " " . $obj_data['MAIN_TABLE'];
            $sql_inner = "";
            foreach ($cond_res as $k => $v) {
                $sql_inner .= " " . "$k = $v and";
            }
            $sql = $sql . " on " . $sql_inner;
            $sql = substr($sql, 0, strrpos($sql, "and", -1));
            $sql .= " where " . $main_object['MAIN_TABLE'] . "." . $main_object['KEY_ATTR_FLD'] . "=" . $post_id['id'];
            return $sql;
        }
        die;
    }

    /**
     * 解析条件数据
     */
    protected function rel_parse($post_json, $obj_data, $lead_attr, $org_id) {
        $post_tbl = $post_json[0]; //根拼接关联有关的数据
        $post_id = $post_json[1]; //根查询条件相关的值
        $post_tbl_primary = $post_tbl['OBJ_TYPE']; //主对象类型
        $post_tbl_data = $post_tbl['REL_TABLES']; //要拼接的条件数组
        $post_cond_id = $post_tbl['COND_ID']; //最终条件
        //第一步查出主对象
        $main_object = $this->obj->getOneData($post_tbl_primary);
        //查出主对象属性信息
        $main_attr = $this->attr->object_attr($org_id, $main_object['OBJ_NAME']);
        //第二步分割条件数据,并且组成数组数据
        $rel_data = explode(";", $post_tbl_data);
        $rel_data = $this->rel_to_arrs($rel_data);
        //第三步拿出主条件
        //select cond_attr,cond_value1,cond_value2 from tc_user_rel_condition where org_id=1 and cond_id = 3003;
        $sql = "select cond_attr,cond_value1,cond_value2,is_reference from tc_user_rel_condition where org_id=$org_id and cond_id = $post_cond_id";
        $cond_data = $this->db->query($sql)->result_array();
        //解析出查询条件
        $cond_res = $this->rel_to_cond($cond_data, $main_attr, $lead_attr, $post_id);
        $sql = " from " . $main_object['MAIN_TABLE'];
        $i = 0;
        foreach ($rel_data as $k => $v) {
            //$v 0=>array(item1,item2,MF|SF|FC),1......
            $i++;
            foreach ($v as $kk => $vv) {
                $str = $vv[2];
                unset($vv[2]);
                $sql .= $this->$str($k, $vv, $main_object, $obj_data, $post_id, $org_id, $i);
            }
        }
        foreach ($cond_res as $k => $v) {
            $sql .= " where " . $main_object['MAIN_TABLE'] . ".$k = $v and";
        }
        $sql = substr($sql, 0, strrpos($sql, "and", -1));
        return $sql;
        //待续......
    }

    /**
     * 主对象属性值与字段名关联
     */
    protected function MF($k, $vv, $main_object, $obj_data, $post_id, $org_id, $i) {
        $rel_w = $k . "." . $vv[1];
        $main_w = $main_object['MAIN_TABLE'] . "." . $main_object['KEY_ATTR_FLD'];
        $sql = " left join $k on " . $rel_w . " = " . $main_w . " and " . $rel_w . " = " . $post_id['id'];
        return $sql;
    }

    /**
     * 子对象ID与字段名关联
     */
    protected function SF($k, $vv, $main_object, $obj_data, $post_id, $org_id, $i) {
        if ($i == 1) {
            $tbl_alias = $obj_data['MAIN_TABLE'];
        } else {
            $tbl_alias = $obj_data['MAIN_TABLE'] . "_" . $i;
        }
        $tbl = $obj_data['MAIN_TABLE'] . " " . $tbl_alias;
        $rel_w = $tbl_alias . "." . $obj_data['KEY_ATTR_FLD'];
        $rel_w1 = $k . "." . $vv[1];
        $sql = " left join $tbl on $rel_w = $rel_w1 and " . $obj_data['MAIN_TABLE'] . ".org_id = " . $org_id;
        return $sql;
    }

    /**
     * 字段名与常量关联
     */
    protected function FC($k, $vv, $main_object, $obj_data, $post_id, $org_id, $i) {
        $sql = " and $k." . $vv[0] . " = " . $vv[1];
        return $sql;
    }

    /**
     * 解析主条件
     * @param type $cond_data 主条件数据 以属性名显示
     * @param type $main_attr 主属性信息数据
     * @param type $lead_attr 子属性信息数据
     * @param type $post_id 主属性属性值
     * @return type 解析后的主条件
     */
    protected function rel_to_emp_cond($cond_data, $main_obj, $main_attr, $rel_obj, $lead_attr, $post_id) {
        $res_data = array();
        foreach ($cond_data as $key => $value) {
            $cond_w = explode(".", $value['COND_ATTR']);
            if (in_array("ObjectID", $cond_w) || in_array("ObjectType", $cond_w)) {
                $res_data[$main_obj['MAIN_TABLE'] . "." . $main_obj["KEY_ATTR_FLD"]] = $rel_obj['MAIN_TABLE'] . "." . $main_obj["KEY_ATTR_FLD"];
            } else {
                if ($value['IS_REFERENCE'] == "1") {
                    $res_data[$rel_obj['MAIN_TABLE'] . "." . $lead_attr[$value['COND_ATTR']]['fld_name']] = $main_obj['MAIN_TABLE'] . "." . $main_attr[$value['COND_VALUE1']]['fld_name'];
                } else {
                    $res_data[$rel_obj['MAIN_TABLE'] . "." . $lead_attr[$value['COND_ATTR']]['fld_name']] = $value['COND_VALUE1'];
                }
            }
        }
        return $res_data;
    }

    /**
     * 解析主条件
     * @param type $cond_data 主条件数据 以属性名显示
     * @param type $main_attr 主属性信息数据
     * @param type $lead_attr 子属性信息数据
     * @param type $post_id 主属性属性值
     * @return type 解析后的主条件
     */
    protected function rel_to_cond($cond_data, $main_attr, $lead_attr, $post_id) {
        $res_data = array();
        foreach ($cond_data as $key => $value) {
            if ($value['IS_REFERENCE'] == "1") {
                $res_data[$main_attr[$value['COND_ATTR']]['fld_name']] = $post_id['id'];
            } else {
                $res_data[$main_attr[$value['COND_ATTR']]['fld_name']] = $value['COND_VALUE1'];
            }
        }
        return $res_data;
    }

    /**
     * 数据转化成相应的格式
     * @param type $rel_arrs 升级后的结构体
     * <rel表名1>:<ITEM1>,<ITEM2>,MF|SF|FC:...;<rel表名1>:<ITEM1>,<ITEM2>,MF|SF|FC:...
     * 其中 MF: 主对象属性值与字段名关联, SF: 子对象ID与字段名关联, FC: 字段名与常量关联
     * array[rel表名] = array(条件0=>array(item1, item2, MF|SF|FC)) 
     */
    protected function rel_to_arrs($rel_arrs) {
        $res_data = array();
        /**
         * rel_opportunity_user :Opportunity.ID,optnty_id,MF:User.ID,user_id,SF;
         * rel_opportunity_stage_user:Opportunity.ID,optnty_id,MF:User.ID,user_id,SF;
         * rel_object_role_user:Opportunity.ID,obj_id,MF:User.ID,user_id,SF:obj_type,4,FC
         */
        foreach ($rel_arrs as $k => $v) {
            $arrs = explode(":", $v);
            $main_key = $arrs[0];
            unset($arrs[0]);
            foreach ($arrs as $kk => $vv) {
                $arrs_vv = explode(",", $vv);
                $res_data[$main_key][$kk] = $arrs_vv;
            }
        }
        return $res_data;
    }

    /**
     * 拉取权限SQL数据
     */
    public function premissionList($objData, $OrgID, $user_id = null, $operation_premiss = "read") {
        $user_id = empty($user_id) ? $this->session->userdata('user_id') : $user_id;
        $premissList = $this->premiss_data_opration($objData, $OrgID, $user_id, $operation_premiss); 
		//取出对应的权限
        $premiss_str = substr($premissList, 0, 1);
        if (empty($premissList)) {
            $premiss_str = 5;
        }
        switch ($premiss_str) {
            case "0"://无权限
                return "1 = 2";
                break;
            case "1"://负责员工
                return $this->Responsible($objData, $OrgID, $user_id, true);
                break;
            case "2"://相关员工
                return $this->Responsible($objData, $OrgID, $user_id);
                break;
            case "3"://本级
                return $this->corresPonding($objData, $OrgID, $user_id, true);
                break;
            case "4"://本级及下级
                return $this->corresPonding($objData, $OrgID, $user_id);
                break;
            case "5"://最大权限
                return "1 = 1";
                break;
        }
        die;
    }
    /**
     * 拉取权限SQL数据 用于更新，不关联主表的情况下
     */
    public function premissionList2($objData, $table, $OrgID, $user_id = null, $operation_premiss = "read") {
        $user_id = empty($user_id) ? $this->session->userdata('user_id') : $user_id;
        $premissList = $this->premiss_data_opration($objData, $OrgID, $user_id, $operation_premiss); 
    //取出对应的权限
        $premiss_str = substr($premissList, 0, 1);
        if (empty($premissList)) {
            $premiss_str = 5;
        }
        switch ($premiss_str) {
            case "0"://无权限
                return "1 = 2";
                break;
            case "1"://负责员工
                return $this->Responsible2($objData, $table, $OrgID, $user_id, true);
                break;
            case "2"://相关员工
                return $this->Responsible2($objData, $table, $OrgID, $user_id);
                break;
            case "3"://本级
                return $this->corresPonding2($objData, $table, $OrgID, $user_id, true);
                break;
            case "4"://本级及下级
                return $this->corresPonding2($objData, $table, $OrgID, $user_id);
                break;
            case "5"://最大权限
                return "1 = 1";
                break;
        }
        die;
    }
    /**
     * 负责员工/相关员工
     */
    public function Responsible2($objData, $table, $OrgID, $user_id, $is_self = FALSE) {
        extract($objData, EXTR_OVERWRITE);
        $sql = strtolower($table) . "." . $KEY_ATTR_FLD . " in (";
        $tbl_name = "rel_" . strtolower($OBJ_NAME) . "_user";
        if (!$is_self) {
            $sql .= " select $tbl_name." . $KEY_ATTR_FLD . " from $tbl_name where $tbl_name.org_id = $OrgID and $tbl_name.user_id = $user_id ";
        } else {
            $sql .= " select $tbl_name." . $KEY_ATTR_FLD . " from $tbl_name where $tbl_name.org_id = $OrgID and $tbl_name.user_id = $user_id and $tbl_name.is_self = 1 ";
        }
        $sql .= ")";
        return $sql;
        die;
    }

    /**
     * 本级/本级及下级
     */
    public function corresPonding2($objData, $table, $OrgID, $user_id, $is_self = FALSE) {
        extract($objData, EXTR_OVERWRITE);
        $sql = "select * from tc_user where user_id = $user_id and org_id = $OrgID";
        $row_data = $this->db->query($sql)->result_array();
        $row_data = $row_data[0];
        $Dept_ID = $row_data['DEPT_ID'];
        $sql = "select * from tc_department where org_id = $OrgID and dept_id = $Dept_ID";
        $dept_data = $this->db->query($sql)->result_array();
        $dept_data = $dept_data[0];
        $tree_path = $dept_data['TREE_PATH'];
        $sql = strtolower($table) . "." . "$KEY_ATTR_FLD in (";
        unset($sql);
        $tbl_user_name = "rel_" . strtolower($OBJ_NAME) . "_user";
        $tbl_privilege_name = "rel_" . strtolower($OBJ_NAME) . "_privilege";
        $sql = strtolower($table) . "." . $KEY_ATTR_FLD . " in (";
        $sql .= " select $tbl_user_name." . $KEY_ATTR_FLD . " from $tbl_user_name where $tbl_user_name.org_id = $OrgID and $tbl_user_name.user_id = $user_id";
        $sql .= " union ";
        if (!$is_self) { //本级及下级
            $sql .= " select $tbl_privilege_name." . $KEY_ATTR_FLD . " from $tbl_privilege_name where $tbl_privilege_name.privilege_id in (select dept_id from tc_department where tc_department.org_id = $OrgID and tc_department.tree_path like '$tree_path%') ";
        } else { //本级
            $sql .= " select $tbl_privilege_name." . $KEY_ATTR_FLD . " from $tbl_privilege_name where $tbl_privilege_name.org_id = $OrgID and $tbl_privilege_name.privilege_id = $Dept_ID ";
        }
        $sql .= ")";
        return $sql;
        die;
    }
    /**
     * Objects_model::premissionAct()
     * 功能权限
     * @param mixed $objData
     * @param mixed $OrgID
     * @param mixed $user_id
     * @param string $act_str
     * @return string
     */
    public function premissionAct($objData, $OrgID, $user_id = null, $act_str = "add") {
        $user_id = empty($user_id) ? $this->session->userdata('user_id') : $user_id;
        $premissList = $this->premiss_data_opration($objData, $OrgID, $user_id, "other"); //取出对应的权限
        switch ($act_str) {
            case "view"://访问
                return substr($premissList, 0, 1);
                break;
            case "add"://新增
                return substr($premissList, 1, 1);
                break;
            case "start"://激活/启用
                return substr($premissList, 2, 1);
                break;
            case "stop"://关闭/停用
                return substr($premissList, 3, 1);
                break;
            case "import"://导入
                return substr($premissList, 4, 1);
                break;
            case "print"://打印
                return substr($premissList, 5, 1);
                break;
            case "export"://导出
                return substr($premissList, 6, 1);
                break;
            case "change"://改变状态
                return substr($premissList, 7, 1);
                break;
            default:
                return "fail";
                break;
        }
        die;
    }

    /**
     * 负责员工/相关员工
     */
    public function Responsible($objData, $OrgID, $user_id, $is_self = FALSE) {
        extract($objData, EXTR_OVERWRITE);
        $sql = strtolower($MAIN_TABLE) . "." . $KEY_ATTR_FLD . " in (";
        $tbl_name = "rel_" . strtolower($OBJ_NAME) . "_user";
        if (!$is_self) {
            $sql .= " select $tbl_name." . $KEY_ATTR_FLD . " from $tbl_name where $tbl_name.org_id = $OrgID and $tbl_name.user_id = $user_id ";
        } else {
            $sql .= " select $tbl_name." . $KEY_ATTR_FLD . " from $tbl_name where $tbl_name.org_id = $OrgID and $tbl_name.user_id = $user_id and $tbl_name.is_self = 1 ";
        }
        $sql .= ")";
        return $sql;
        die;
    }

    /**
     * 本级/本级及下级
     */
    public function corresPonding($objData, $OrgID, $user_id, $is_self = FALSE) {
        extract($objData, EXTR_OVERWRITE);
        $sql = "select * from tc_user where user_id = $user_id and org_id = $OrgID";
        $row_data = $this->db->query($sql)->result_array();
        $row_data = $row_data[0];
        $Dept_ID = $row_data['DEPT_ID'];
        $sql = "select * from tc_department where org_id = $OrgID and dept_id = $Dept_ID";
        $dept_data = $this->db->query($sql)->result_array();
        $dept_data = $dept_data[0];
        $tree_path = $dept_data['TREE_PATH'];
        $sql = strtolower($MAIN_TABLE) . "." . "$KEY_ATTR_FLD in (";
        unset($sql);
        $tbl_user_name = "rel_" . strtolower($OBJ_NAME) . "_user";
        $tbl_privilege_name = "rel_" . strtolower($OBJ_NAME) . "_privilege";
        $sql = strtolower($MAIN_TABLE) . "." . $KEY_ATTR_FLD . " in (";
        $sql .= " select $tbl_user_name." . $KEY_ATTR_FLD . " from $tbl_user_name where $tbl_user_name.org_id = $OrgID and $tbl_user_name.user_id = $user_id";
        $sql .= " union ";
        if (!$is_self) { //本级及下级
            $sql .= " select $tbl_privilege_name." . $KEY_ATTR_FLD . " from $tbl_privilege_name where $tbl_privilege_name.privilege_id in (select dept_id from tc_department where tc_department.org_id = $OrgID and tc_department.tree_path like '$tree_path%') ";
        } else { //本级
            $sql .= " select $tbl_privilege_name." . $KEY_ATTR_FLD . " from $tbl_privilege_name where $tbl_privilege_name.org_id = $OrgID and $tbl_privilege_name.privilege_id = $Dept_ID ";
        }
        $sql .= ")";
        return $sql;
        die;
    }

    /**
     * 拉取相关权限
     */
    public function premiss_data_opration($objData, $OrgID, $user_id, $operation_premiss) {
        extract($objData, EXTR_OVERWRITE);
        $w = "";
        $sql = "select read_privilege,edit_privilege,delete_privilege,other_privilege from tc_user_scope where user_id = $user_id and org_id = $OrgID and obj_type = $OBJ_TYPE";
        $row = $this->db->query($sql)->result_array();
        $fields = strtoupper($operation_premiss . "_privilege");
        return $row[0][$fields];
        die;
    }

    /**
     * parse_w 解析条件数组
     * @param int 	$OrgID	  标识ID
     * @param array $Where 条件数据
     * @return array 返回解析后的条件数据
     */
    private function parse_w($OrgID, $Where) {
        $LeadAttr = $this->attr->object_attr(1, NULL);
        extract($Where, EXTR_OVERWRITE); //释放数组数据 where rel
        $sql_w = array();
        $obj_data = $this->obj->ObjParseData($this->obj->NameGetObj(1, NULL, TRUE));
        for ($i = count($where); $i > 0; $i--) {
            $rel = str_replace($i, "rel_" . $i, $rel);
        }
        foreach ($where as $k => $v) {
            $attr_values = ConverArray($v['attr'], '.');
            $s_attr_values = $attr_values;
            //判断传进来的数据是否是正常可以查询,如果不是则需要做再次判断,先判断第一个键值是否与当前模块类型相等如果不等则是外部引用
            //Lead.Owner.Name
            if (count($attr_values) > 2) {
                $this->parseAttr($LeadAttr, $obj_data, $attr_values, $v, $sql);

                $res_data = $this->db->query($sql)->result_array();
                $str_values = implode_r(array(","), $res_data);
                $fld = $LeadAttr[$LeadAttr[$s_attr_values[0] . "." . $s_attr_values[1]]['referred_by']]['fld_name'];
                $v['value'] = $str_values;
                $v['action'] = 'INC';
                //此处明天继续
            } else {
                $fld = $LeadAttr[$v["attr"]]['fld_name'];
                if (empty($fld)) {
                    $attr_values = $LeadAttr[$v["attr"]];
                    if ($attr_values["attr_type"] == 4 && $attr_values["is_ref_obj"] == 1) {
                        $fld = $LeadAttr[$LeadAttr[$v["attr"]]["referred_by"]]["fld_name"];
                    }
                }
            }
            switch ($v['action']) {
                case 'NULL'; //为空 同is null
                    $w = $fld . ' IS NULL';
                    break;
                case 'NOT_NULL': //不为空 同is not null
                    $w = $fld . ' IS NOT NULL';
                    break;
                case 'NOT_EQUAL': //不等于 同<>
                    $w = $fld . ' != \'' . $v['value'] . '\'';
                    break;
                case 'GT': //大于 同>
                    $w = $fld . ' > \'' . $v['value'] . '\'';
                    break;
                case 'GT_EQUAL': //大于等于 同>=
                    $w = $fld . ' >= \'' . $v['value'] . '\'';
                    break;
                case 'LE': //小于 同<
                    $w = $fld . ' < \'' . $v['value'] . '\'';
                    break;
                case 'EQUAL': //等于 同=
                    $equal_value = isset($v['value']) ? $v['value'] : $v['value2'];
                    $w = $fld . ' = \'' . $equal_value . '\'';
                    break;
                case 'LE_EQUAL': //小于等于 同<=
                    $w = $fld . ' <= \'' . $v['value'] . '\'';
                    break;
                case 'INC': //涵盖 同in
                    $v['value'] = str_replace(',', '\',\'', $v['value']);
                    $w = $fld . ' IN(\'' . $v['value'] . '\')';
                    break;
                case 'NOT_INC': //不涵盖 同not in
                    $v['value'] = str_replace(',', '\',\'', $v['value']);
                    $w = $fld . ' NOT IN(\'' . $v['value'] . '\')';
                    break;
                case 'RANGE': //区间 同>= and <=
                    if (preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/", $v['value2'])) {
                        //是日期时间类型，给成 23:59:59
                        if (strlen($v['value2']) <= 10 and strlen($v['value2']) >= 8) {
                            $v['value2'] = $v['value2'] . " 23:59:59";
                        }
                    }
                    $w = $fld . ' >=' . "to_date('" . $v['value1'] . "', 'yyyy-mm-dd hh24:mi:ss')" . ' AND ' . $fld . ' <= ' . "to_date('" . $v['value2'] . "', 'yyyy-mm-dd hh24:mi:ss')";
                    break;
                case 'LIKE':
                    $w = $fld . " LIKE '%" . $v['value'] . "%'";
                    break;
                case 'NOT_LIKE': //不包含 同not like
                    $w = $fld . " NOT LIKE '%" . $v['value'] . "%'";
                    break;
                case 'BEFORE': //早于，用于日期时间类型
                    $w = $fld . " < " . "to_date('" . $v['value'] . "', 'yyyy-mm-dd hh24:mi:ss')" . "";
                    break;
                case 'AFTER': //晚于，用于日期时间类型
                    $w = $fld . " > " . "to_date('" . $v['value'] . "', 'yyyy-mm-dd hh24:mi:ss')" . "";
                    break;
                case 'RECENT': //最近，用于日期时间类型
                    $w = $fld . " >= " . "to_date('" . date("Y-m-d H:i:s", strtotime("-$v[value1] $v[value2]")) . "', 'yyyy-mm-dd hh24:mi:ss')" . "";
                    break;
                case 'FUTURE': //未来，用于日期时间类型
                    $w = $fld . " >= " . "to_date('" . date("Y-m-d H:i:s", strtotime("+$v[value1] $v[value2]")) . "', 'yyyy-mm-dd hh24:mi:ss')" . "";
                    break;
                case '1':
                    $w = " 1 = 1 ";
                    break;
                default:
                    $w = $fld . ' = \'' . $v['value'] . '\'';
                    break;
            }
            if (!empty($w)) {
                $sql_w[$k] = $w;
                $rel = str_replace("rel_" . $k, $w, $rel);
            }
        }
        return $rel;
    }

    /**
     * 递归取出最终要查询的字段和条件
     * 
     */
    private function parseAttr($LeadAttr, $obj_data, &$attr_values, $value, &$sql) {
        $fruit = array_shift($attr_values);
        foreach ($attr_values as $k => $v) {
            if ($LeadAttr[$fruit . "." . $v]['attr_type'] == "4" && $LeadAttr[$fruit . "." . $v]['is_ref_obj'] == "1") {
                $attr_values[$k] = $LeadAttr[$fruit . "." . $v]['ref_obj_name'];
                $this->parseAttr($LeadAttr, $obj_data, $attr_values, $value, $sql);
            } else {
                $tbl_name = $LeadAttr[$fruit . "." . $v]['tbl_name'];
                $fld_name = $LeadAttr[$fruit . "." . $v]['fld_name'];
                $obj_name = $LeadAttr[$fruit . "." . $v]['ref_obj_name'];
                $KEY_ATTR_FLD = $obj_data[$fruit]['KEY_ATTR_FLD'];
                $sql = "select " . $KEY_ATTR_FLD . " from " . $tbl_name . " where ";

                switch ($value['action']) {
                    case 'NULL'; //为空 同is null
                        $sql .= $fld_name . ' IS NULL';
                        break;
                    case 'NOT_NULL': //不为空 同is not null
                        $sql .= $fld_name . ' IS NOT NULL';
                        break;
                    case 'NOT_EQUAL': //不等于 同<>
                        $sql .= $fld_name . ' != \'' . $value['value'] . '\'';
                        break;
                    case 'GT': //大于 同>
                        $sql .= $fld_name . ' > \'' . $value['value'] . '\'';
                        break;
                    case 'GT_EQUAL': //大于等于 同>=
                        $sql .= $fld_name . ' >= \'' . $value['value'] . '\'';
                        break;
                    case 'LE': //小于 同<
                        $sql .= $fld_name . ' < \'' . $value['value'] . '\'';
                        break;
                    case 'EQUAL': //等于 同=
                        $sql = $fld_name . ' = \'' . $value['value'] . '\'';
                        break;
                    case 'LE_EQUAL': //小于等于 同<=
                        $sql .= $fld_name . ' <= \'' . $value['value'] . '\'';
                        break;
                    case 'INC': //涵盖 同in
                        $value['value'] = str_replace(',', '\',\'', $value['value']);
                        $sql .= $fld_name . ' IN(\'' . $value['value'] . '\')';
                        break;
                    case 'NOT_INC': //不涵盖 同not in
                        $value['value'] = str_replace(',', '\',\'', $value['value']);
                        $sql .= $fld_name . ' NOT IN(\'' . $value['value'] . '\')';
                        break;
                    case 'RANGE': //区间 同>= and <=
                        if (preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/", $value['value2'])) {
                            //是日期时间类型，给成 23:59:59
                            if (strlen($value['value2']) <= 10 and strlen($value['value2']) >= 8) {
                                $value['value2'] = $value['value2'] . " 23:59:59";
                            }
                        }
                        $sql .= $fld . ' >=' . "to_date('" . $value['value1'] . "', 'yyyy-mm-dd hh24:mi:ss')" . ' AND ' . $fld . ' <= ' . "to_date('" . $value['value2'] . "', 'yyyy-mm-dd hh24:mi:ss')";
                        break;
                    case 'LIKE':
                        $sql .= $fld_name . " LIKE '%" . $value['value'] . "%'";
                        break;
                    case 'NOT_LIKE': //不包含 同not like
                        $sql .= $fld_name . " NOT LIKE '%" . $value['value'] . "%'";
                        break;
                    case 'BEFORE': //早于，用于日期时间类型
                        $sql .= $fld . " < " . "to_date('" . $value['value'] . "', 'yyyy-mm-dd hh24:mi:ss')" . "";
                        break;
                    case 'AFTER': //晚于，用于日期时间类型
                        $sql .= $fld . " > " . "to_date('" . $value['value'] . "', 'yyyy-mm-dd hh24:mi:ss')" . "";
                        break;
                    case 'RECENT': //最近，用于日期时间类型
                        $sql .= $fld . " >= " . "to_date('" . date("Y-m-d H:i:s", strtotime("-$value[value1] $value[value2]")) . "', 'yyyy-mm-dd hh24:mi:ss')" . "";
                        break;
                    case 'FUTURE': //未来，用于日期时间类型
                        $sql .= $fld . " >= " . "to_date('" . date("Y-m-d H:i:s", strtotime("+$value[value1] $value[value2]")) . "', 'yyyy-mm-dd hh24:mi:ss')" . "";
                        break;
                    case '1':
                        $sql .= " 1=1 ";
                        break;
                    default:
                        $sql .= $fld_name . ' = \'' . $value['value'] . '\'';
                        break;
                }
            }
        }
    }

    

    /**
     * GetListColNames 获取列表的ColName关系json
     * @param array $ListFormat 列表布局
     * @param array $LeadAttr 线索的所有属性信息
     * @return json 返回前台调用的json数组
     */
    public function GetListColNames($ListFormat, $LeadAttr, $obj_data, $org_id = 1, $lang_id = 2) {
        $res_data = $this->listColNames($ListFormat, $LeadAttr, $obj_data, $org_id, $lang_id);
        return json_encode($res_data);
    }
	
	/**
	 * 查询列表头中文名称
	 */
    protected function listColNames($ListFormat, $LeadAttr, $obj_data, $org_id = 1, $lang_id = 2) {
        $res_data = array();
        $i = 0;
        foreach ($ListFormat as $k => $v) {
            $attrValues = ConverArray($v, ".");
            if (count($attrValues) > 2) {
                $str_attrs = $this->attr->object_attr(1, $attrValues[1]);
                $str_values = $attrValues[1] . "." . $attrValues[2];
                $res_data[$i] = $str_attrs[$str_values]["label"];
                if (empty($res_data[$i])) {
                    //进入第二种 查询方式
                    $attr_name = $attrValues[0] . "." . $attrValues[1];
                    $sql = "select * from dd_attribute where attr_name = '$attr_name' and org_id =$org_id";
                    $res_attr_data = $this->db->query($sql)->result_array();
                    $res_attr_data = $res_attr_data[0];
                    $obj_name = $res_attr_data['REF_OBJ_NAME'];

                    $dict_name = $obj_name . "." . $attrValues[2];
                    $sql = "select * from dd_dict_str where org_id = $org_id and dict_name = '$dict_name' and lang_id = $lang_id";
                    $res_dict_data = $this->db->query($sql)->result_array();
                    $res_dict_data = $res_dict_data[0];

                    $sql = "select * from dd_dict_str where org_id = $org_id and dict_name = '$obj_name' and lang_id = $lang_id";
                    $res_obj_data = $this->db->query($sql)->result_array();
                    $res_obj_data = $res_obj_data[0];

                    $res_data[$i] = $res_obj_data['LABEL'] . "-" . $res_dict_data['LABEL'];
                }
            } else {
                $res_data[$i] = $LeadAttr[$v]["label"];
            }
            $i++;
        }
        return $res_data;
    }

    /**
     * 	GetDataType					解析数据中数据类型字段
     * 	@param	array	$data		需要解析的数据
     * 	@param	array	$LeadAttr	查询出的属性
     * 	@return	array				返回的数组
     */
    public function GetDataType($data, $LeadAttr, $OrgID) {
        foreach ($data as $k => $v) {
            foreach ($v as $kk => $vv) {
                $res_data = $this->paraseType($LeadAttr[$kk], $kk, $vv, $OrgID, $data[$k][$kk], true);

                if (is_array($res_data)) {

                    foreach ($res_data as $r_k => $r_v) {
                        $d_v = $kk . "." . $r_k;
                        $data[$k][$d_v] = $r_v;
                    }
                } else {
                    $data[$k][$kk] = $res_data;
                }
            }
        }
        return $data;
    }
    
    /**
     * 获取高级查询数据下拉列表
     * @param type $SeniorQueryAttrs    传入的参数
     * @return type 返回查询出的结果
     */
    public function getListsAttr($SeniorQueryAttrs) {
        extract($SeniorQueryAttrs, EXTR_OVERWRITE); //释放数组元素
        $obj_data = $this->obj->NameGetObj($org_id, $obj_name);
        extract($obj_data, EXTR_OVERWRITE); //释放数组元素
        //获取列表需要显示的字段
        $list_format = $this->format->GetListFormat($org_id, $OBJ_TYPE, $user_id);
        //查询列表数据
        $lead_attr = $this->attr->object_attr($org_id, $obj_name);
        $ColNames = $this->GetListColNames($list_format, $lead_attr, $obj_data);
        $data["ColModel"] = $this->GetListColModel($list_format, $ColNames, $obj_data, $OBJ_TYPE);
        $data["SeniorQueryAttrJson"] = $this->getSeniorQueryAttrs($org_id, $LangID, $obj_name, $obj_data, $lead_attr);
        return $data;
    }

    /**
     * 获取高级查询数据下拉列表
     * @param type $SeniorQueryAttrs    传入的参数
     * @return type 返回查询出的结果
     */
    public function getAjaxListsAttr($SeniorQueryAttrs, $is_format = false, $func_name = null) {
        extract($SeniorQueryAttrs, EXTR_OVERWRITE); //释放数组元素
        $obj_data = $this->obj->NameGetObj($org_id, $obj_name);
        extract($obj_data, EXTR_OVERWRITE); //释放数组元素
        //获取列表需要显示的字段
        $list_format = empty($list_formats) ? $this->format->GetListFormat($org_id, $OBJ_TYPE, $user_id) : $list_formats;
        //查询列表数据
        $lead_attr = $this->attr->object_attr($org_id, $obj_name);
        $data["obj_data"] = $obj_data;
        $colNames = $this->GetListColNames($list_format, $lead_attr, $obj_data);
        $data["ColModel"] = $this->GetListColModel($list_format, $colNames, $obj_data, $OBJ_TYPE, 200, 25, "array", $func_name);
        if($obj_name == 'Type'){
            $data["ColModel"] = array(
              'Col'=>array(
                0 =>array(
                  'Name' => 'Type.Name',
                  'LangEn' => '类型名称'
                )
              ),
              'KeyAttrName' => 'Type.ID'
            );
        }
        if ($is_format) {
            return $data;
        }
        //按键功能独立出来 高级查询数据独立
        $data["SeniorQueryAttrJson"] = $this->getSeniorQueryAttrs($org_id, $LangID, $obj_name, $obj_data, $lead_attr);
        return $data;
    }
    
    /**
     * Objects_model::list_object_col()
     * 判断相关对象列表头部是否有附加字段
     * @param mixed $data
     * @param mixed $sqid
     * @return void
     */
    public function list_object_col( & $data, $sqid) {
        switch ($sqid) {
            case "99":
                $this->rel_user_list($data);
                break;
        }
    }
    
    /**
     * Objects_model::rel_user_list()
     * 附加相关用户对象列表头部字段
     * @param mixed $data
     * @return void
     */
    protected function rel_user_list( & $data ) {
        $responsible_user = array(
            "Name" => "User.IsMaster",
            "LangEn" => "是否为相关员工或负责员工",
        );
        $data['ColModel']['Col'][] = $responsible_user;
    }
    
    /**
     * GetListColModel 获取列表的ColModel关系json
     * @param array $ListFormat 列表布局
     * @param array $Width 每个栏位的宽度 默认200
     * @return json 返回前台调用的json数组
     */
    public function GetListColModel($ListFormat, $colNames, $obj_data, $obj_type, $Width = 200, $OperationWidth = 80, $r_type = "array", $func_name = null) {
        $res_data = array();
        $res_data['Col'] = array();
        $colNames = json_decode($colNames, true);
        foreach ($ListFormat as $k => $v) {
            if ($v == $obj_data['NAME_ATTR_NAME']) {
                if (empty($func_name)) {
                    $res_data['Col'][] = array(
                        'Name' => $v,
                        'LangEn' => $colNames[$k],
                        'Url' => site_url("www/objects/view") . "?id={" . $obj_data['KEY_ATTR_NAME'] . "}&obj_type=" . $obj_type,
                    );
                } else {
                    if($obj_type==10){
                        $res_data['Col'][] = array(
                            'Name' => $v,
                            'LangEn' => $colNames[$k],
                            'Url' => site_url("www/objects/view") . "?id={" . $obj_data['KEY_ATTR_NAME'] . "}&obj_type=" . 14,
                        );
                    }else{
                        $res_data['Col'][] = array(
                            'Name' => $v,
                            'LangEn' => $colNames[$k],
                            'Url' => site_url("www/objects/view") . "?id={" . $obj_data['KEY_ATTR_NAME'] . "}&obj_type=" . $obj_type,
                        );

                    }
                }
            } else {
                $res_data['Col'][] = array(
                    'Name' => $v,
                    'LangEn' => $colNames[$k],
                );
            }
        }
        $res_data['KeyAttrName'] = $obj_data["KEY_ATTR_NAME"];
        switch ($r_type) {
            case "array":
                return $res_data;
                break;
            case "json":
                return json_encode($res_data);
                break;
        }
        return json_encode($res_data);
        die;
    }

    /**
     * @param $obj_name 对象名称
     * @return type  对象所有的属性
     */
    public function all_attr($obj_name) {
        $obj_data = $this->obj->NameGetObj("1", $obj_name);
        $lead_attr = $this->attr->object_attr("1", $obj_name);
        return $this->getSeniorQueryAttrs("1", "2", $obj_name, $obj_data, $lead_attr);
    }

    /**
     * 获取AJAX查询列表
     * @param type $org_id 标识ID
     * @param type $LangID 语言ID
     * @param type $obj_name 对象名称
     * @param type $obj_data 对象结果集
     * @return type 返回数据
     */
    public function getSeniorQueryAttrs($org_id, $LangID, $obj_name, $obj_data, $lead_attr) {
        $DictAttr = _HashData($this->obj->NameGetCnDict($org_id, $LangID), "DICT_NAME");
        /**
         * 1 首先使用DISTANCT筛选出不重复的REFOBJNAME属性字段,并且调用getattrquery方法完成一次遍历查询 查询结果使用对象名+数组形式
         * 2 完成1步后再根据getattrquery 传入 bool值 与1步查询出来的数据 进行查询并且解析
         * 3 完成1 2 两步以后 调用解析自定义函数把相关数组转成一维数组并且将原因加入到二维数组中的值转换成一维
         */
        $dis_data = $this->getAttrDistanct($org_id, $obj_name, $obj_data, $DictAttr, $lead_attr);
        $object_attr = $this->getAttrQuery($org_id, $obj_name, $obj_data, $DictAttr, $lead_attr, true, $dis_data);
        $data["SeniorQueryAttrJson"] = $this->getObjAttrs($object_attr);
        return json_encode($data["SeniorQueryAttrJson"]);
    }

    /**
     * 
     * @param type $org_id 标识ID
     * @param type $obj_name 对象名称
     * @param type $obj_data 对象结果集
     * @param type $DictAttr 对象对应的中文名称
     * @param type $isbool  标识BOOL类型
     * @param type $dis_data 查询DISTANCT字段的结果集
     * @return type
     */
    private function getAttrQuery($org_id, $obj_name, $obj_data, $DictAttr, $lead_attr, $isbool = false, $dis_data = null) {
        return $this->GetSeniorQueryAttr($org_id, $obj_name, $obj_data, $DictAttr, $lead_attr, $isbool, $dis_data);
    }

    /**
     * GetSeniorQueryAttr 获取高级查询属性字段
     * @params int $objId 标识ID
     * @params string $objName 标识名称
     * @params array $ObjData 对象名称 
     * @params array $DictAttr 对象对应的中文名称
     * @params bool  $isbool   BOOL标识符
     * @params array $dis_data 查询DISTANCT字段的结果集
     * @return array 返回数据
     */
    private function GetSeniorQueryAttr($OrgID, $objName, $ObjData, $DictAttr, $lead_attr, $isbool = false, $dis_data = null) {
        /**
         * 1 先查询出关于objname的所有属性
         * 2 循环遍历并且判断查询数据类型如果为引用和枚举则需要查询出相应的属性值
         * 3 返回数组给控制器
         */
        $object_attr = $this->attr->object_attr($OrgID, $objName, true);
        foreach ($object_attr as $k => &$v) {
            $this->paraseQueryType($k, $OrgID, $v, $ObjData, $DictAttr, $lead_attr, $isbool, $dis_data);
        }
        return $object_attr;
    }

    /**
     * 解析高级查询最终结果方法
     * @param type $object_attr 待解析数据
     * @return type 返回解析后的数组s
     */
    private function getObjAttrs($object_attr) {
        $i = 0;
        foreach ($object_attr as $k => $v) {
            $data[$i]['attr_type'] = $v['attr_type'];
            $data[$i]['name'] = $k;
            $data[$i]['label'] = $v['label'];
            if (isset($v['enum_arr'])) {
                foreach ($v['enum_arr'] as $kk => $vv) {
                    $data[$i]['form_data']['enum_arr'][$kk]['enum_key'] = $vv['ENUM_KEY'];
                    $data[$i]['form_data']['enum_arr'][$kk]['enum_value'] = $vv['ENUM_VALUE'];
                }
            }
            if (isset($v['attr_ref'])) {
                $data[$i]['form_data']['ObjName'] = $v['attr_ref'][$k . ".ObjName"];
                $data[$i]['form_data']['RefUrl'] = $v['attr_ref'][$k . ".RefUrl"];
                $data[$i]['form_data']['DialogWidth'] = $v['attr_ref'][$k . ".DialogWidth"];
            }
            $i++;
        }
        foreach ($object_attr as $key => $value) {
            foreach ($value as $k => $v) {
                if (is_array($v) && !empty($v) && strpos($k, ".")) {
                    $data[$i]['attr_type'] = $v['attr_type'];
                    $data[$i]['name'] = $k;
                    $data[$i]['label'] = $v['label'];
                    if (isset($v['enum_arr'])) {
                        foreach ($v['enum_arr'] as $kk => $vv) {
                            $data[$i]['form_data']['enum_arr'][$kk]['enum_key'] = $vv['ENUM_KEY'];
                            $data[$i]['form_data']['enum_arr'][$kk]['enum_value'] = $vv['ENUM_VALUE'];
                        }
                    }
                    if (isset($v['attr_ref'])) {
                        $ref_key = $value['ref_key'] . "." . end(explode(".", $k));
                        $data[$i]['form_data']['ObjName'] = $v['attr_ref'][$ref_key . ".ObjName"];
                        $data[$i]['form_data']['RefUrl'] = $v['attr_ref'][$ref_key . ".RefUrl"];
                        $data[$i]['form_data']['DialogWidth'] = $v['attr_ref'][$ref_key . ".DialogWidth"];
                    }
                    $i++;
                }
            }
        }
        return $data;
    }

    /**
     * 根据distanct查询
     * @param type $org_id  标识ID
     * @param type $obj_name 对象名称
     * @param type $obj_data 对象结果集
     * @param type $DictAttr 对象对应的中文名称结果集
     * @return type 返回数组
     */
    private function getAttrDistanct($org_id, $obj_name, $obj_data, $DictAttr, $lead_attr) {
        $dis_data = _ConverOneArray($this->attr->object_attr_distnct($obj_name));
        $res_data = array();
        foreach ($dis_data as $k => $v) {
            $obj_data = $this->obj->NameGetObj($org_id, $v);
            $res_data[$v] = $this->getAttrQuery($org_id, $v, $obj_data, $DictAttr, $lead_attr);
        }
        return $res_data;
    }

    /**
     * paraseQueryType 解析高级查询属性类型
     * @param   string      $query_key      属性键值
     * @param   int         $objID          标识ID
     * @param   array       $query_value    属性详细数据
     * @param   array       $ObjData        对象详细数据
     * @param   array       $DictAttr       对象对应的中文名称数据
     * @return  array       返回的数组
     */
    private function paraseQueryType($query_key, $OrgID, &$query_value, $ObjData, $DictAttr, $lead_attr, $isbool = false, $dis_data = null) {
        extract($query_value, EXTR_OVERWRITE); //释放数组
        /*
         * 注：
         * 1 如果判断为引用类型需把当前类型值由4变更为19
         * 2 如果判断为枚举需注意查询此属性对应的是否有公用枚举值如果有查询公用枚举值的数据如果没有查询当前属性值的数据
         */
        /* @var $attr_type type */
        switch ($attr_type) {
            case 4; //短字符型 判断是否为引用
                $this->queryIsRefObj($query_key, $OrgID, $query_value, $ObjData, $DictAttr, $lead_attr, $isbool, $dis_data);
                break;
            //判断是否为枚举
            case 11://CHECK
            case 12://RADIO
            case 13://下拉单选
                $this->queryIsEnum($query_key, $OrgID, $query_value, $lead_attr);
                break;
            default:
                return $query_value;
                break;
        }
    }

    /**
     * 	GetDataType		解析数据类型字段
     * 	@param	array	$LeadAttr		查询出的属性
     * 	@param	array	$attr_key		数组KEY条件
     * 	@param	array	$attr_value		数组VALUE条件
     * 	@param	array	$OrgID			组织ID
     * 	@return	array				返回的数组
     */
    public function paraseType($LeadAttr, $attr_key, $attr_value, $OrgID, &$datas, $is_ref = FALSE) {

        $typeId = $LeadAttr["attr_type"]; //取出数据类型
        $is_ref_obj = $LeadAttr["is_ref_obj"]; //取出数据类型是否为引用
        switch ($typeId) {
            /* 待写
              case 2://百分数
              break;
              case 3://金额保留二位小数
              break;
             */
            case 4://短字符型 判断是否为引用
                return $this->isRefObj($is_ref_obj, $attr_key, $LeadAttr, $OrgID, $attr_value, $datas, $is_ref);
                break;
            //单选枚举
            case 11://CHECK
                $this->is_check($attr_value);
                return $attr_value;
                break;
            case 12://RADIO
            case 13://下拉单选
                return $this->isEnum($attr_key, $attr_value, $OrgID, $LangId, $AttrName, $EnumKey, $datas, $is_ref);
                break;
            /* 待写
              case 14://多选枚举
              return $attr_value;
              break;
             */
            case 15://日期型
                return $this->isDate($datas, 8 * 60 * 60);
                break;
            case 16://时间型
                return $this->isDateTime($datas, 8 * 60 * 60);
                break;
            default://其他
                if (is_object($attr_value)) {
                    $blob = !empty($attr_value) ? base64_decode($attr_value->load()) : base64_decode($attr_value);

                    return $blob;
                } else {

                    return $attr_value;
                }
                return $attr_value;
                break;
        }
    }

    /**
     * @param $v 把数组单选值变动
     */
    public function is_check(&$v) {
        if ($v == 1) {
            $v = "是";
        } else {
            $v = "否";
        }
    }

    /**
     * 	isRefObj 判断引用并且实现返回数据
     * 	@params	int		$is_ref_obj	标识,判断是否为引用
     * 	@params	array	$LeadAttr	线索的所有属性信息
     * 	@parmas	int		$OrgID		组织ID
     * 	@params	int		$attr_value	条件数据
     * 	@params	array	$datas		数据引用
     * 	@return	array	返回数组数据
     */
    private function isRefObj($is_ref_obj, $attr_key, $LeadAttr, $OrgID, $attr_value, &$datas, $is_ref) {
        /*
          如果判断为引用
          1 查询出dd_object中NAME_ATTR_NAME,MAIN_TABLE,KEY_ATTR_FLD字段
          2 根据查询出来的NAME_ATTR_NAME字段通过dd_attribute表进行查询,查询出TBL_NAME,FLD_NAME,OBJ_NAME
          3 再利用OBJ_NAME动态加载MODEL方式 加载ORM文件 保证获取有效数据 以TBL_NAME为表名 以FLD_NAME为字段名 进行查询
         */
        if ($is_ref_obj == "1") {
            //object
			/**
			取出REF_OBJ_NAME名称是否为 ReferObject 如果为是 并且包含 privilege就把where里加入privilege95
			*/
			$refer_object = $LeadAttr["ref_obj_name"];//取出REF_OBJ_NAME名称
			$tbl_name = $LeadAttr['tbl_name'];//取出表名			
            $ObjName = $LeadAttr["ref_obj_name"]; //取obj_name名称
            $obj_data = $this->obj->NameGetObj($OrgID, $ObjName);
            //attribute
            $AttrBean["select"] = "TBL_NAME,FLD_NAME,OBJ_NAME"; //字段
            //条件
            $AttrBean["where"]["attr_name"] = $obj_data["NAME_ATTR_NAME"];
            $AttrBean["where"]["org_id"] = $OrgID;
            //开始查询
            $attr_data = $this->attr->SelectOne($AttrBean);
			$where = array(
                $obj_data["KEY_ATTR_FLD"] => $attr_value,
                "org_id" => $OrgID,
            );
			//有待优化
			if ($refer_object == "ReferObject" && (strpos($tbl_name, "privilege"))) {
				//证明要查询的是管理维度
				$where['obj_type'] = 95;
			}
			//有待优化
            $select = array(
                $attr_data["FLD_NAME"],
            );
            $this->db->select($select);
            $this->db->where($where);
            $this->db->from($attr_data["TBL_NAME"]);
            $data = $this->db->get()->result_array();
            if ($is_ref) {
                $datas = @current($data[0]);
                $datas = empty($datas) ? "" : $datas;
            }
            return $data;
        } else {
            return $attr_value;
        }
    }

    /**
     * 	isEnum	判断是否枚举并且实现返回数据
     * 	@params	int		$attr_key	查询条件
     * 	@params	array	$LangId		语言类型
     * 	@parmas	int		$OrgID		组织ID
     * 	@params	string	$EnumKey	查询条件
     * 	@params	int		$attr_value	条件数据
     * 	@params	array	$AttrName	查询条件
     * 	@return	array	返回数组数据
     */
    private function isEnum($attr_key, $attr_value, $OrgID, $LangId, $AttrName, $EnumKey, &$datas, $is_ref) {
        /*
          单选操作如下：
          1 如果判断为单选操作 需要以$attr_key为条件查询dd_attribute出共用枚举类型字段ENUM_ATTR_NAME
          2 如果共用枚举类型字段有值则下步过滤以共用字段为主反之以当前字段为主
          3 以上判断完成后以最终结果过滤查询tc_enum_str并且加lang_id=从SESSION中获取LANG_ID and org_id = 1
          4 最终返回数据类型数组
         */
        //attribute
        $AttrBean["select"] = "ENUM_ATTR_NAME"; //字段
        //条件
        $AttrBean["where"]["attr_name"] = $attr_key;
        $AttrBean["where"]["org_id"] = $OrgID;
        //开始查询
        $attr_data = $this->attr->SelectOne($AttrBean);
        //查询完成结果集中只有ENUM_ATTR_NAME字段信息
        //再查询tc_enum_str表得出要的数据
        $LangId = $this->session->userdata("lang_id");
        $LangId = empty($LangId) ? 2 : $LangId;
        $AttrName = empty($attr_data["ENUM_ATTR_NAME"]) ? $attr_key : $attr_data["ENUM_ATTR_NAME"];
        $EnumKey = $attr_value;
        $r_data = $this->enum->SelectOneStr($OrgID, $LangId, $AttrName, $EnumKey);
        if ($is_ref) {
            $datas = @reset($r_data);
            $datas = empty($datas) ? "" : $datas;
        }
		if ($attr_key == "RelPrivilege.RelatedToType") {//如果是管理维度 单独处理
			return "部门";
		}
        return $r_data;
    }

    /**
     * 	isDate					日期转换
     * 	@params	date	$date	要转换的日期
     * 	@parmas	int		$experis时间间隔
     * 	@return	date			返回日期格式数据
     */
    public function isDate($date, $experis) {
        $res_date = empty($date) ? null : date("Y-m-d", strtotime($date) + $experis);
        return $res_date;
    }

    /**
     * 	isDateTime				日期时间转换
     * 	@params	date	$date	要转换的日期
     * 	@parmas	int		$experis时间间隔
     * 	@return	date			返回日期格式数据
     */

    public function isDateTime($DateTime, $experis) {
        $res_date = empty($DateTime) ? null : date("Y-m-d H:i:s", strtotime($DateTime) + $experis);
        return $res_date;
//        return date("Y-m-d h:i:s", strtotime($DateTime) + $experis);
    }

    /**
     * 解析待处理字段
     * @param type $ListFormat 待处理字段
     */
    protected function parse_formart(&$ListFormat) {
        $LeadAttr = $this->attr->object_attr(1, NULL);
        $obj_data = $this->obj->ObjParseData($this->obj->NameGetObj(1, NULL, TRUE));
        $for_data = array();
        foreach ($ListFormat as $k => &$v) {
            $attr_values = ConverArray($v, '.');
            $s_attr_values = $attr_values;
            if (count($attr_values) > 2) {
                $this->parse_formart_attr($LeadAttr, $obj_data, $attr_values, $for_data);
                $for_data['history_data'][$k] = $ListFormat[$k];
                unset($ListFormat[$k]);
            }
        }
        return $for_data;
        die;
    }

    protected function parse_formart_attr($LeadAttr, $obj_data, &$attr_values, &$for_data) {
        $fruit = array_shift($attr_values);
        foreach ($attr_values as $k => $v) {
            if ($LeadAttr[$fruit . "." . $v]['attr_type'] == "4" && $LeadAttr[$fruit . "." . $v]['is_ref_obj'] == "1") {
                $for_data[] = $LeadAttr[$fruit . "." . $v]['ref_obj_name'];
                $this->parse_formart_attr($LeadAttr, $obj_data, $attr_values, $for_data);
            } else {
                $for_data['new_data'][] = $fruit . "." . $v . "";
            }
        }
    }

    /**
     * GetTbl						解析要查询的表
     * @param	array	$NeedAttr	需要的过滤的属性
     * @param	array	$LeadAttr	要解析的数据
     * @return	array				返回的数组
     */
    private function GetTbl($NeedAttr, $LeadAttr) {
        $res_data = array(); //要返回的数组信息
        foreach ($NeedAttr as $k => $v) {
            $res_data[$k] = $LeadAttr[$v]["tbl_name"];
        }
        return array_filter(array_unique($res_data));
    }

    /**
     * GetFld						解析要查询的字段
     * @param	array	$NeedAttr	需要的过滤的属性
     * @param	array	$LeadAttr	要解析的数据
     * @return	array				返回的数组
     */
    private function GetFld($NeedAttr, $LeadAttr) {
        $res_data = array(); //要返回的数组信息
        $fld_que = ""; //存储每一次的字段名
        foreach ($NeedAttr as $k => $v) {
            if (!empty($LeadAttr[$v]["attr_type"])) {
                switch ($LeadAttr[$v]["attr_type"]) {
                    case 4://短字符型 判断是否为引用
                        if (!empty($LeadAttr[$v]["referred_by"])) {
                            $res_data[$k] = $LeadAttr[$LeadAttr[$v]["referred_by"]]["tbl_name"] . "." . $LeadAttr[$LeadAttr[$v]["referred_by"]]["fld_name"] . " " . $LeadAttr[$LeadAttr[$v]["referred_by"]]["fld_name"] . "__" . $k;
                            ;
                        } else {
                            $res_data[$k] = $LeadAttr[$v]["tbl_name"] . "." . $LeadAttr[$v]["fld_name"] . " " . $LeadAttr[$v]["fld_name"] . "__" . $k;
                        }
                        break;
                    case 15://日期型
                        $res_data[$k] = "to_char(" . $LeadAttr[$v]["tbl_name"] . "." . $LeadAttr[$v]["fld_name"] . ", 'yyyy-mm-dd hh24:ii:ss') as " . $LeadAttr[$v]["fld_name"] . "__" . $k;
                        break;
                    case 16://时间型
                        $res_data[$k] = "to_char(" . $LeadAttr[$v]["tbl_name"] . "." . $LeadAttr[$v]["fld_name"] . ", 'yyyy-mm-dd hh24:ii:ss') as " . $LeadAttr[$v]["fld_name"] . "__" . $k;
                        break;
                    default://其他
                        if (!empty($LeadAttr[$v]["fld_name"])) {
                            $res_data[$k] = $LeadAttr[$v]["tbl_name"] . "." . $LeadAttr[$v]["fld_name"] . " " . $LeadAttr[$v]["fld_name"] . "__" . $k;
                            ;
                        }
                        break;
                }
            }
        }

        return array_unique($res_data);
    }

    

    /**
     * queryIsRefObj 高级查询判断类型
     * @param string $query_key   属性名称
     * @param int    $objID         标识ID
     * @param array  $query_value 属性详细数据
     * @param array  $DictAttr      对象对应的数据
     * @param bool   $isbool        bool 标识
     * @param array $dis_data DISTANCET结果集
     * @return array    返回查询出来的数据
     */
    private function queryIsRefObj($query_key, $OrgID, &$query_value, $ObjData, $DictAttr, $lead_attr, $isbool = false, $dis_data = null) {
        extract($query_value, EXTR_OVERWRITE);
        if ($is_ref_obj == "1") {
            $query_value["attr_type"] = $this->RefType;
            $query_value["attr_ref"] = $this->RefObjDialog($query_key, $query_value, $OrgID, $ObjData, $DictAttr);
            if ($isbool) {
                $ref_obj_name = $query_value['ref_obj_name'];
                $lee_is_query = $query_value['lee_is_query'];
                $referred_by = $lead_attr[$query_value['referred_by']]["refer_to"];
                $attr_ref = $query_value["attr_ref"];
                $label = $query_value["label"];
                $query_value["ref_key"] = $ref_obj_name;
                foreach ($dis_data[$ref_obj_name] as $k => $v) {
                    $v['label'] = $label . "：" . $v['label'];
                    $iks = explode(".", $k);
                    $iks_str = end($iks);
                    $query_value[$referred_by . "." . $iks_str] = $v;
                }
            }
        }
    }

    /**
     * 引用数据处理
     * @param type $QueryKey 数组键值
     * @param type $QueryValue 数组值
     * @param type $OrgID 标识ID
     * @param type $ObjData 数据集
     * @param type $DictAttr 对象对应的中文名称结果集
     * @return type 返回的数组
     */
    public function RefObjDialog($QueryKey, $QueryValue, $OrgID, $ObjData, $DictAttr) {
        //对象查到表
        $obj_data = $this->obj->NameGetObj($OrgID, $QueryValue['ref_obj_name']);
        //属性名转换为字段名
        $AttrBean["select"] = "TBL_NAME,FLD_NAME,OBJ_NAME"; //字段
        $AttrBean["where"]["attr_name"] = $obj_data["NAME_ATTR_NAME"];
        $AttrBean["where"]["org_id"] = $OrgID;
        $attr_data = $this->attr->SelectOne($AttrBean);
        //查出值
        $fld_name = strtoupper($LeadAttr[$v['referred_by']]['fld_name']);
        $data[$QueryKey . '.ObjName'] = $DictAttr[$attr_data["OBJ_NAME"]]["LABEL"];
        $data[$QueryKey . '.RefUrl'] = $this->RefUrl . $obj_data['OBJ_TYPE'] . "&robj_type=" . $ObjData['OBJ_TYPE'];
        $data[$QueryKey . '.DialogWidth'] = $this->DialogWidth;
        return $data;
    }

    /**
     * queryIsEnum 高级查询判断类型
     * @param string $query_key     属性名称
     * @param int    $objID         标识ID
     * @param array  $query_value   属性详细数据
     * @return array    返回查询出来的数据
     */
    public function queryIsEnum($query_key, $OrgID, &$query_value, $lead_attr) {
        /*
          单选操作如下：
          1 如果判断为单选操作 需要以$attr_key为条件查询dd_attribute出共用枚举类型字段ENUM_ATTR_NAME
          2 如果共用枚举类型字段有值则下步过滤以共用字段为主反之以当前字段为主
          3 以上判断完成后以最终结果过滤查询tc_enum_str并且加lang_id=从SESSION中获取LANG_ID and org_id = 1
          4 最终返回数据类型数组
         */
        //attribute

        $AttrBean["select"] = "ENUM_ATTR_NAME"; //字段
        //条件
        $AttrBean["where"]["attr_name"] = $query_key;
        $AttrBean["where"]["org_id"] = $OrgID;
        //开始查询
        $attr_data = $this->attr->SelectOne($AttrBean);
        //查询完成结果集中只有ENUM_ATTR_NAME字段信息
        //再查询tc_enum_str表得出要的数据
        $LangId = 2; //$this->session->userdata("lang_id");
        $AttrName = empty($attr_data["ENUM_ATTR_NAME"]) ? $query_key : $attr_data["ENUM_ATTR_NAME"];
        $query_data = $this->enum->SelectWhere($OrgID, $LangId, $AttrName);
        $query_value["enum_arr"] = $query_data;
    }

    /**
     * GetAll 查出对象所有内容
     * @param $OrgID    组织id
     * @param $LeadID   线索id
     * return array $data_all
     */
    public function GetAll($OrgID, $ObjID, $ObjName) {
        $data = array();
        $obj_data = $this->obj->NameGetObj($OrgID, $ObjName);

        $table_name = $this->GetAllTbl($OrgID, $ObjName);
        $AttrBean['where'] = array('ATTR_NAME' => $ObjName . ".ID");
        $AttrBean['select'] = 'FLD_NAME';
        $dddd = $this->attr->SelectOne($AttrBean); //主键字段名
        //$sql="SELECT * FROM tc_opportunity_attr WHERE org_id=1 and optnty_id=1195991";
        // $sql="SELECT * FROM tc_opportunity_attr WHERE org_id=1 and optnty_id>10783 and optnty_id<10803";
        foreach ($table_name as $key => $value) {
            $content = @$this->db->from($value)->where("org_id=$OrgID and " . $dddd['FLD_NAME'] . "=$ObjID")->get()->result_array();
            if (!empty($content)) {
                $data[] = $content[0];
            }
        }
        $data_all = array();
        if (count($data) > 1) {
            for ($i = 0; $i <= count($data) - 1; $i++) {
                $data_all = array_merge($data_all, $data[$i]);
            }
        } else {
            $data_all = $data[0];
        }
        return $data_all;
    }

    /**
     * GetAllTbl 查出对象对应的所有表
     * @param $OrgID    组织id
     * @param $LeadID   线索id
     * return array $content
     */
    public function GetAllTbl($OrgID, $ObjName) {
        $attr_data = $this->attr->object_attr($OrgID, $ObjName);
        foreach ($attr_data as $key => $value) {
            $table_name[] = $value['tbl_name'];
        }
        $table_name = array_unique($table_name);
        return $table_name;
    }
    /**
     *ForType type单独处理
     *
     */
    public function ForType($org_id,$obj_type){
        $where = array(
            'org_id' => $org_id,
            'obj_type' => $obj_type
            );
        $data = $this->db->select('type_id as ,type_name')->from('tc_type')->where($where)->get()->result_array();
        foreach ($data as $k => $v) {
            $data_arr[$k]['enum_value'] = $v['TYPE_NAME'];
            $data_arr[$k]['enum_key'] = $v['TYPE_ID'];
        }
        return $data_arr;
    }

    /**
     * Getinfo 查出对象所有内容
     * @param $OrgID    组织id
     * @param $LeadAttr  属性信息
     * @param $LeadID   线索id
     * @param $is_edit  是否为编辑页面
     * return array $lead_data
     */
    public function Getinfo($ObjName, $OrgID, $DictAttr, $ObjAttr, $ObjID, $UserID = "", $TypeID = "", $is_edit = false, $newdata = array()) {
        if ($ObjID) {
            $content = $this->GetAll($OrgID, $ObjID, $ObjName);
        }

        $tobj_data = $this->obj->NameGetObj($OrgID, $ObjName);
        foreach ($ObjAttr as $k => $v) {
            switch ($v['attr_type']) {
                case 4://是否为引用
                    if ($v['is_ref_obj']) {
                        if ($v["ref_obj_name"] == "ReferObject") {
                            $SRC_ID = $content[strtoupper($ObjAttr[$v["referred_by"]]["fld_name"])];
                            if ($SRC_ID == "0" || empty($SRC_ID)) {
                                $object_data[$k] = "";
                                break;
                            } else {
                                /**
                                 *  ReferObject特殊处理
                                 *  1 得到值 然后根据对象查询到 得到值的类型 例如对象名为account 查询account表根据src_id得到src_type
                                 *  2 由1得出dd_object的值 再根据SRC_TYPE 从dd_object取出obj_name值
                                 */
                                $lrot = array('org_id'=>$OrgID,'attr_name'=>$k);
                                $lrotdata = $this->db->select('lee_refer_obj_type as lrot')->from('dd_attribute')->where($lrot)->get()->result_array();
                                if(empty($lrotdata[0]['LROT'])){
                                    $ff = strtoupper($ObjAttr[$k . "Type"]['fld_name']);
                                }else{
                                    $ff = strtoupper($ObjAttr[$lrotdata[0]['LROT']]['fld_name']);
                                }
                                
                                $obj_data = $this->obj->getOneData($content[$ff]);
                            }
                        } else {

                            //对象查到表
                            $RefObjName = $v["ref_obj_name"]; //取obj_name名称
                            $obj_data = $this->obj->NameGetObj($OrgID, $RefObjName);
                        }

                        //属性名转换为字段名
                        $AttrBean["select"] = "TBL_NAME,FLD_NAME,OBJ_NAME"; //字段
                        $AttrBean["where"]["attr_name"] = $obj_data["NAME_ATTR_NAME"];
                        $AttrBean["where"]["org_id"] = $OrgID;
                        $attr_data = $this->attr->SelectOne($AttrBean);

                        //查出值
                        $fld_name = strtoupper($ObjAttr[$v['referred_by']]['fld_name']);
                        if (isset($content)) {
                            $where = array(
                                $obj_data["KEY_ATTR_FLD"] => $content[$fld_name],
                                "org_id" => $OrgID,
                            );
                            $select = array(
                                $attr_data["FLD_NAME"],
                            );

                            $this->db->select($select);
                            $this->db->where($where);
                            $this->db->from($attr_data["TBL_NAME"]);
                            $data = $this->db->get()->result_array();
                            $object_data[$k] = $data[0][strtoupper($attr_data["FLD_NAME"])];
                            $object_data[$k . '.Name'] = $data[0][strtoupper($attr_data["FLD_NAME"])];
                            $object_data[$k . '.ObjName'] = $DictAttr[$attr_data["OBJ_NAME"]]["LABEL"];
                            $object_data[$k . '.RefUrl'] = $this->RefUrl . $obj_data['OBJ_TYPE'] . "&robj_type=" . $tobj_data['OBJ_TYPE'];
                            $object_data[$k . '.DialogWidth'] = $this->DialogWidth;
                            $object_data[$k . '.ID'] = $content[$fld_name];

                        } else {
                            $object_data[$k . '.RefUrl'] = $this->RefUrl . $obj_data['OBJ_TYPE'] . "&robj_type=" . $tobj_data['OBJ_TYPE'];
                            $object_data[$k . '.Name'] = "";
                            $object_data[$k . '.ObjName'] = $DictAttr[$attr_data["OBJ_NAME"]]["LABEL"];
                            $object_data[$k . '.DialogWidth'] = $this->DialogWidth;
                            $object_data[$k . '.ID'] = "";
                            $object_data[$k] = "";
                        }
                    } else {
                        $fld_name = strtoupper($v['fld_name']);
                        if (!empty($fld_name)) {
                            if (isset($content)) {
                                if (is_object($content[$fld_name])) {
                                    $content[$fld_name] = base64_decode($content[$fld_name]->load());
                                }
                                $object_data[$k] = $content[$fld_name];
                            } else {
                                $object_data[$k] = "";
                            }
                        } else {
                            $object_data[$k] = "";
                        }
                    }
                    break;
                case 11:
                    $fld_name = strtoupper($v['fld_name']);
                    if(!empty($fld_name)){
                        if (isset($content)) {
                            if ($content[$fld_name]==1) {
                                $object_data[$k] = '是';
                                $object_data[$k . '.enum_key'] = 1;
                                $object_data[$k . '.value'] = '是';
                            }elseif($content[$fld_name]==0) {
                                $object_data[$k] = '否';
                                $object_data[$k . '.enum_key'] = 0;
                                $object_data[$k . '.value'] = '否';
                            }else{
                                $object_data[$k] = "";
                                $object_data[$k . '.enum_key'] = "";
                                $object_data[$k . '.value'] = "";
                            }
                        }

                    }
                    break;
                case 12:
                    $AttrBean["select"] = "ENUM_ATTR_NAME"; //字段
                    $AttrBean["where"]["attr_name"] = $k;
                    $AttrBean["where"]["org_id"] = $OrgID;
                    $attr_data = $this->attr->SelectOne($AttrBean);
                    $LangId = 2;
                    $AttrName = empty($attr_data["ENUM_ATTR_NAME"]) ? $k : $attr_data["ENUM_ATTR_NAME"];
                    $fld_name = strtoupper($v['fld_name']);
                    if (!empty($content[0][$fld_name])) {

                        $enum = $this->enum->SelectOneStr($OrgID, $LangId, $AttrName, $content[$fld_name]);

                        $object_data[$k] = $enum['ENUM_VALUE'];
                        $object_data[$k . '.enum_key'] = $content[$fld_name];
                        $object_data[$k . '.value'] = $enum['ENUM_VALUE'];
                    } else {
                        $object_data[$k] = "";
                        $object_data[$k . '.enum_key'] = "";
                        $object_data[$k . '.value'] = "";
                    }
                    if ($is_edit) {

                        $enum_arr = $this->enum->GetEnumArr($OrgID, $AttrName, $LangID = 2);
                        $object_data[$k . '.attr_type'] = $v['attr_type'];
                        $object_data[$k . '.enum_arr'] = $enum_arr;
                    }
                    break;
                case 13://下拉单选查出枚举值
                    $AttrBean["select"] = "ENUM_ATTR_NAME"; //字段
                    $AttrBean["where"]["attr_name"] = $k;
                    $AttrBean["where"]["org_id"] = $OrgID;
                    $attr_data = $this->attr->SelectOne($AttrBean);
                    $LangId = 2;
                    $AttrName = empty($attr_data["ENUM_ATTR_NAME"]) ? $k : $attr_data["ENUM_ATTR_NAME"];
                    $fld_name = strtoupper($v['fld_name']);
                    if (!empty($content[$fld_name])) {

                        $enum = $this->enum->SelectOneStr($OrgID, $LangId, $AttrName, $content[$fld_name]);

                        $object_data[$k] = $enum['ENUM_VALUE'];
                        $object_data[$k . '.enum_key'] = $content[$fld_name];
                        $object_data[$k . '.enum_attr_name'] = $AttrName;
                        $object_data[$k . '.value'] = $enum['ENUM_VALUE'];
                    } else {
                        $object_data[$k] = "";
                        $object_data[$k . '.enum_key'] = "";
                        $object_data[$k . '.enum_attr_name'] = $AttrName;
                        $object_data[$k . '.value'] = "";
                    }
                    if ($is_edit) {
                        $enum_arr = $this->enum->GetEnumArr($OrgID, $AttrName, $LangID = 2);
                        $object_data[$k . '.attr_type'] = $v['attr_type'];
                        $object_data[$k . '.enum_attr_name'] = $AttrName;
                        $object_data[$k . '.enum_arr'] = $enum_arr;
                    }
                    break;
                case 14:
                    break;
                case 15://日期型
                    $fld_name = strtoupper($v['fld_name']);
                    if (isset($content)) {
                        $content[$fld_name] = $this->ToChar($fld_name, $v['tbl_name'], $OrgID, $ObjID, $ObjName);
                        if (empty($content[$fld_name])) {
                            $object_data[$k] = "";
                        } else {
                            $object_data[$k] = $this->isDate($content[$fld_name], 8 * 60 * 60);
                        }
                    } else {
                        $object_data[$k] = "";
                    }
                    break;
                case 16://时间型
                    $fld_name = strtoupper($v['fld_name']);
                    if(!empty($fld_name)){
                        if (isset($content)) {
                            $content[$fld_name] = $this->ToChar($fld_name, $v['tbl_name'], $OrgID, $ObjID, $ObjName);
                            if (empty($content[$fld_name])) {
                                $object_data[$k] = "";
                            } else {
                                $object_data[$k] = $this->isDateTime($content[$fld_name], 8 * 60 * 60);
                            }
                        } else {
                            $object_data[$k] = "";
                        }
                    }
                    
                    break;
                default:
                    $fld_name = strtoupper($v['fld_name']);
                    if (!empty($fld_name)) {
                        if (isset($content)) {
                            if (is_object($content[$fld_name])) {
                                $ddd = base64_decode($content[$fld_name]->load());

                                if (empty($ddd)) {
                                    $content[$fld_name] = "";
                                } else {
                                    $content[$fld_name] = $ddd;
                                }
                            }
                            $object_data[$k] = $content[$fld_name];
                        } else {
                            $object_data[$k] = "";
                        }
                    } else {
                        $object_data[$k] = "";
                    }
                    break;
            }
        }

        //新增页面自动载入对象类型ID和当前用户的数据（用户和部门）
        if (empty($ObjID)) {

            if (!empty($TypeID)) {
                $this->load->model('www/type_model', 'type');
                $typedata = $this->type->SelectOne($OrgID, $TypeID);
                $object_data[$ObjName.'.TypeID'] = $TypeID;
                $object_data[$ObjName.'.Type.Name'] = $typedata['Name'];
                $object_data[$ObjName.'.Type'] = $typedata['Name'];
                $object_data[$ObjName.'.Type.ID'] = $TypeID;
            }
            if (!empty($UserID)) {

                $userdata = $this->user->SelectOne($OrgID, $UserID);
                if($ObjName=='Task'){
                    $object_data[$ObjName.'.AssignedToID'] = $UserID;
                    $object_data[$ObjName.'.AssignedTo.Name'] = $userdata['Name'];
                    $object_data[$ObjName.'.AssignedTo'] = $userdata['Name'];
                    $object_data[$ObjName.'.AssignedTo.ID'] = $UserID;
                }else{
                    $object_data[$ObjName.'.OwnerID'] = $UserID;
                    $object_data[$ObjName.'.Owner.Name'] = $userdata['Name'];
                    $object_data[$ObjName.'.Owner'] = $userdata['Name'];
                    $object_data[$ObjName.'.Owner.ID'] = $UserID;
                }
                $object_data[$ObjName.'.CreatedByID'] = $UserID;
                $object_data[$ObjName.'.CreatedBy.Name'] = $userdata['Name'];
                $object_data[$ObjName.'.CreatedBy'] = $userdata['Name'];
                $object_data[$ObjName.'.CreatedBy.ID'] = $UserID;
                
                if(!empty($userdata['Dept_ID'])){
                    $DeptId=$userdata['Dept_ID'];
                    $deptdata=$this->dept->SelectOne($OrgID, $DeptId);
                    if($ObjName=='Contact'){
                        $object_data[$ObjName.'.OwnerDeptID'] = $DeptId;
                        $object_data[$ObjName.'.OwnerDept'] = $deptdata['Name'];
                        $object_data[$ObjName.'.OwnerDept.Name'] = $deptdata['Name'];
                        $object_data[$ObjName.'.OwnerDept.ID'] = $DeptId;
                    }else{
                        $object_data[$ObjName.'.DepartmentID'] = $DeptId;
                        $object_data[$ObjName.'.Department'] = $deptdata['Name'];
                        $object_data[$ObjName.'.Department.Name'] = $deptdata['Name'];
                        $object_data[$ObjName.'.Department.ID'] = $DeptId;
                    }
                }
            }
        }
        if (!empty($newdata)) {
            foreach ($newdata as $k => $v) {
                $object_data[$k] = $v;
            }
        }
        return $object_data;
    }

    private function ToChar($fld_name, $tbl_name, $OrgID, $ObjID, $ObjName) {
        $obj_data = $this->obj->NameGetObj($OrgID, $ObjName);

        $AttrBean['where'] = array('ATTR_NAME' => $ObjName . ".ID");
        $AttrBean['select'] = 'FLD_NAME';
        $dddd = $this->attr->SelectOne($AttrBean); //主键字段名

        $this->db->select("to_char(" . $fld_name . ", 'yyyy-mm-dd hh24:ii:ss') as d");
        $this->db->from($tbl_name);
        $where = array(
            $dddd['FLD_NAME'] => $ObjID,
            'ORG_ID' => $OrgID
        );
        $this->db->where($where);
        $dddd = $this->db->get()->result_array();
        return($dddd[0]['D']);
    }

    public function RelAttrRoleAct($data,$act){
        foreach ($data as $k => $v) {
            switch ($act) {
                case "is_self_edit"://负责员工(不可编辑)
                    $r_data[$v['ATTR_NAME']] = substr($v['CONTROL'], 0, 1);
                    break;
                case "is_self_view"://负责员工(不可查看)
                    $r_data[$v['ATTR_NAME']] = substr($v['CONTROL'], 1, 1);
                    break;
                case "not_self_edit"://相关员工(不可编辑)
                    $r_data[$v['ATTR_NAME']] = substr($v['CONTROL'], 2, 1);
                    break;
                case "not_self_view"://相关员工(不可查看)
                    $r_data[$v['ATTR_NAME']] = substr($v['CONTROL'], 3, 1);
                    break;
                case "other_edit"://其他人员(不可编辑)
                    $r_data[$v['ATTR_NAME']] = substr($v['CONTROL'], 4, 1);
                    break;
                case "other_view"://其他人员(不可查看)
                    $r_data[$v['ATTR_NAME']] = substr($v['CONTROL'], 5, 1);
                    break;
                default:
                    $r_data[$v['ATTR_NAME']] = "";
                    break;
            }
        }
        return $r_data;
    }
    /**
     * 是否相关员工、负责员工,用于不可编辑
     * @param $obj_id 
     * @param $user_id 
     */
    public function IsSelfEdit($org_id,$obj_name,$obj_id,$user_id,$pk){
        $where = array(
            'org_id' => $org_id,
            $pk => $obj_id,
            'user_id' => $user_id
            );
        $data = $this->db->from('rel_'.$obj_name.'_user')->where($where)->get()->result_array();
        if(empty($data)){
            return "other_edit";
        }elseif($data[0]['is_self']==1){
            return "is_self_edit";
        }else{
            return "not_self_edit";
        }
    }
    /**
     * GetInfoEdit 查看页面单个线索数据
     * @param int $OrgID 组织ID
     * @param array $ViewFormat 查看页面布局
     * @param array $LeadAttr 线索的所有属性信息
     * @param int $LeadID 线索ID
     * @return json 返回前台调用的json数组
     */
    public function GetInfoEdit($org_id, $ViewFormat, $lead_data, $LeadAttr, $lead_id, $enum_attr,$stop_flag="",$role_attr_arr=array()) {
        $lang_id = $this->session->userdata('lang_id');
        foreach ($ViewFormat['tables'] as $k => $v) {
            foreach ($v['cells'] as $key => $value) {
                if (!empty($value['name'])) {
                    if ($value['label'] == 1) {
                        $ViewFormat['tables'][$k]['cells'][$key]['attr_label'] = $LeadAttr[$value['name']]['label'];
                    } elseif ($value['label'] == 2) {
                        $ViewFormat['tables'][$k]['cells'][$key]['attr_label'] = $value['name'][$lang_id];
                    } else {

                        $ViewFormat['tables'][$k]['cells'][$key]['content'] = $lead_data[$value['name']];
                        if ($LeadAttr[$value['name']]['is_ref_obj']) {
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['id_value'] = $lead_data[$value['name'] . ".ID"];
                            $ViewFormat['tables'][$k]['cells'][$key]['attr_type'] = $this->RefType;
                        } else {
                            $ViewFormat['tables'][$k]['cells'][$key]['attr_type'] = $LeadAttr[$value['name']]['attr_type'];
                        }


                        $ViewFormat['tables'][$k]['cells'][$key]['form_data']['value'] = $lead_data[$value['name']];
                        $ViewFormat['tables'][$k]['cells'][$key]['form_data']['name'] = $value['name'];
                        //是否为必填，综合布局和表字段给前台传值
                        if ($value['required'] || $LeadAttr[$value['name']]['is_must']) {
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['required'] = 1;
                        } else {
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['required'] = 0;
                        }

                        $ViewFormat['tables'][$k]['cells'][$key]['form_data']['notedit'] = $LeadAttr[$value['name']]['notedit'];
                        
                        if($stop_flag==1){
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['notedit'] = 1;
                        }
                        if(!empty($role_attr_arr)){
                            if(isset($role_attr_arr[$value['name']]) && $role_attr_arr[$value['name']]==1){
                                $ViewFormat['tables'][$k]['cells'][$key]['form_data']['notedit'] = 1;                              
                            }
                        }

                        if (isset($lead_data[$value['name'] . '.enum_arr'])) {
                            //$ViewFormat['tables'][$k]['cells'][$key]['enum_arr'] = $lead_data[$value['name'].'.enum_arr'];
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['key'] = isset($lead_data[$value['name'] . ".enum_key"]) ? $lead_data[$value['name'] . ".enum_key"] : "";
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['enum_arr'] = $lead_data[$value['name'] . '.enum_arr'];
                        }
                        if ($LeadAttr[$value['name']]['is_ref_obj']) {
                            $ViewFormat['tables'][$k]['cells'][$key]['referred_by'] = $LeadAttr[$value['name']]['referred_by'];
                            $ViewFormat['tables'][$k]['cells'][$key]['ref_obj_name'] = $LeadAttr[$value['name']]['ref_obj_name'];
                        }
                        if (isset($lead_data[$value['name'] . '.ObjName'])) {
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['ObjName'] = $lead_data[$value['name'] . ".ObjName"];
                        }
                        if (isset($lead_data[$value['name'] . '.RefUrl'])) {
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['RefUrl'] = $lead_data[$value['name'] . '.RefUrl'];
                        }
                        if (isset($lead_data[$value['name'] . '.DialogWidth'])) {
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['DialogWidth'] = $lead_data[$value['name'] . '.DialogWidth'];
                        }
                        $value_name = $value['name'];
                        if (isset($lead_data[$value['name'] . ".enum_attr_name"])) {
                            $value_name = $lead_data[$value['name'] . ".enum_attr_name"];
                        }
                        if (in_array($value_name, $enum_attr['parent'])) {
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['is_parent'] = 1;
                        } else {
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['is_parent'] = 0;
                        }
                        if (in_array($value_name, $enum_attr['child'])) {
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['is_child'] = 1;
                        } else {
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['is_child'] = 0;
                        }
                    }
                } else {
                    if ($value['label']) {
                        $ViewFormat['tables'][$k]['cells'][$key]['attr_label'] = "";
                    } else {
                        $ViewFormat['tables'][$k]['cells'][$key]['content'] = "";
                    }
                }
            }
        }
        return $ViewFormat;
    }

    /**
     * GetInfoView 查看页面单个线索数据
     * @param int $OrgID 组织ID
     * @param array $ViewFormat 查看页面布局
     * @param array $LeadAttr 线索的所有属性信息
     * @param int $LeadID 线索ID
     * @return json 返回前台调用的json数组
     */
    public function GetInfoView($org_id, $ViewFormat, $lead_data, $LeadAttr, $lead_id) {
        $lang_id = $this->session->userdata('lang_id');
        foreach ($ViewFormat['tables'] as $k => $v) {
            foreach ($v['cells'] as $key => $value) {
                if (!empty($value['name'])) {
                    if ($value['label'] == 1) {
                        $ViewFormat['tables'][$k]['cells'][$key]['attr_label'] = $LeadAttr[$value['name']]['label'];
                    } elseif ($value['label'] == 2) {
                        $ViewFormat['tables'][$k]['cells'][$key]['attr_label'] = $value['name'][$lang_id];
                    } else {
                        $ViewFormat['tables'][$k]['cells'][$key]['content'] = $lead_data[$value['name']];
                    }
                } else {
                    if ($value['label']) {
                        $ViewFormat['tables'][$k]['cells'][$key]['attr_label'] = "";
                    } else {
                        $ViewFormat['tables'][$k]['cells'][$key]['content'] = "";
                    }
                }
            }
        }
        return $ViewFormat;
    }

    /**
     * 操作数据初台化方法
     * @param array $post_data   需要操作的POST数据含 有数据 行为方法 模型类
     * @param array $get_data    需要操作的POST数据含 有数据 行为方法 模型类
     */
    public function init($post_data) {
        /*
         * 从传过来的数据获取需要使用的数据模型并获取行为动作
         */
        //extract($get_post_data, EXTR_OVERWRITE);

        if (empty($post_data)) {
            return _msg("fail", "数据不能为空");
        } else {
            $action = strtolower($post_data["module_action"]); //行为动作 add
            $data = $this->$action($post_data);
            return $data;
        }
    }
    
    private function module_update($post_data) {

        //extract($get_post_data, EXTR_OVERWRITE);

        $module = $post_data["module_model"];  //模型文件 Lead
        $obj_id = $post_data[$module . '.ID'];
        unset($post_data[$module . '.ID']);
        unset($post_data["module_model"]);
        unset($post_data["module_action"]);

        $OrgID = $this->session->userdata('org_id');


        //$table_name = $this->GetAllTbl($OrgID, $module); //查出对象表
        $object_attr = $this->attr->object_attr($OrgID, $module); //查出对象对应属性信息
        $obj = $this->obj->NameGetObj($OrgID, $module);
        $content = $this->GetOldInfo($module, $OrgID, $object_attr, $obj_id); //
        foreach ($post_data as $k => $v) {
            if ($object_attr[$k]['is_must'] && empty($post_data[$k])) {
                $ret['res'] = 'fail';
                $ret['msg'] = "保存失败！" . $object_attr[$k]['label'] . "为必填字段。";
                return $ret;
            }
            // if ($object_attr[$k]['attr_type'] == 11){
            //     if($v=='是'){
            //         $post_data[$k] = 1;
                  
            //     }else{
            //         $post_data[$k] = 0;
            //     }
            // }
        }
        $data = array();

        //对比新老数据
        foreach ($post_data as $k => $v) {

            if (isset($content[$k . '.enum_key'])) {

                if ($v != $content[$k . '.enum_key']) {
                    $data[$k] = $v;
                    $data[$k . '.enum_key'] = $v;
                }
            } else {
                if ($v != $content[$k]) {

                    $data[$k] = $v;
                }
            }
        }


        $where[$obj['KEY_ATTR_FLD']] = $obj_id;
        $where['org_id'] = $OrgID;
        $UserID = $this->session->userdata('user_id');
        $return = $this->_update($data, $where, $object_attr, $module, $OrgID, $UserID,$content);
        if($module == 'Task'){
            $user_id = $data[$module . '.AssignedTo.ID'];
        }else{
            $user_id = $data[$module . '.Owner.ID'];
        }
        if(!empty($user_id)){
          
            $u_where = array(
                'org_id' => $OrgID,
                'user_id' => $user_id
                );
            $dept_id = $this->db->select('dept_id')->from('tc_user')->where($u_where)->get()->result_array();
            if(count($dept_id)>1 || count($dept_id)==0){
                $r_data['res'] = 'fail';
                $r_data['msg'] = "保存失败！这个员工没有部门";
                return $r_data;
            }else{
                $d_where = array(
                  'org_id' => $OrgID,
                  'dept_id' => $dept_id[0]['DEPT_ID']
                  );
                $dept_name = $this->db->select('dept_name')->from('tc_department')->where($d_where)->get()->result_array();
                if($module=='Contact'){
                    $data[$module . '.OwnerDept'] = $dept_name[0]['DEPT_NAME'];
                    $data[$module . '.OwnerDept.ID'] = $dept_id[0]['DEPT_ID'];
                }elseif($module == 'Task'){
                    //没有相关管理维度
                }else{
                    $data[$module . '.Department'] = $dept_name[0]['DEPT_NAME']; 
                    $data[$module . '.Department.ID'] = $dept_id[0]['DEPT_ID']; 
                }
            }
        }else{
          unset($data[$module . '.OwnerDept']);
          unset($data[$module . '.Department']);
          unset($data[$module . '.OwnerDept.ID']);
          unset($data[$module . '.Department.ID']);
        }

        if ($return['res'] == 'suc') {

            if ($this->history($data, $obj_id, $content, $object_attr, $module, $UserID)) {
                $r_data['res'] = 'suc';
                $r_data['msg'] = "保存成功！" ;
            } else {
                $r_data['res'] = 'fail';
                $r_data['msg'] = "保存失败！";
            }
        } else {
            $r_data['res'] = 'fail';
            $r_data['msg'] = "保存失败！";
        }
        return $r_data;
    }

    private function module_add($post_data, $org_id = 1) {
        $module = $post_data["module_model"];  //模型文件 Lead


        unset($post_data["module_model"]);
        unset($post_data["module_action"]);

        //$post_data = $this->attr->UnderlineToPoint($post_data);

        $OrgID = $org_id;

        $UserID = $this->session->userdata('user_id');


        $object_attr = $this->attr->object_attr($OrgID, $module);//查出对象对应属性信息
        foreach ($post_data as $k => $v) {
            if ($object_attr[$k]['is_must'] && empty($post_data[$k])) {
                $ret['res'] = 'fail';
                $ret['msg'] = "保存失败！" . $object_attr[$k]['label'] . "为必填字段。";
                return $ret;
                die;
            }
        }

        $return = $this->_add($post_data, $module,$OrgID,$UserID);
		if ($return['res'] == 'fail') {
            return $return;
            die;
		}
	    $func = __FUNCTION__;
	    $this->is_method_exits($return['id'], $module, $func, $org_id);
        return $return;
    }

    /**
     * 判断 MODULE模型里的FUNC方法是否存在并且加载模型文件
     * @param type $module 模型
     * @param string $func 方法名称
     */
    public function is_method_exits($return, $module, $func, $org_id = 1) {
        /*
         * 判断此MODEL文件是否存在如果存在分发到不同的业务层去做处理
         */
        if ($this->require_path($module)) {
            $m = strtolower($module);
            $func = $m . "_" . $func;
            if (method_exists($this->$m, $func)) {
                $this->$m->$func($return, $module, $org_id);
            }
        }
        
    }

    /**
     * 加载模型文件
     * @param type $module 模型名称
     * @return boolean 返回是否存在
     */
    protected function require_path($module) {
        $directory_separator = DIRECTORY_SEPARATOR;
        $www_path = FCPATH . "application" . $directory_separator . "models" . $directory_separator . "www" . $directory_separator . strtolower($module) . "_model.php";
        $admin_path = FCPATH . "application" . $directory_separator . "models" . $directory_separator . "admin" . $directory_separator . strtolower($module) . "_model.php";
        $m = strtolower($module);
        if (file_exists($www_path)) {
            $this->load->model("www/" . $m . "_model", $m);
            return true;
        } elseif (file_exists($admin_path)) {
            $this->load->model("admin/" . $m . "_model", $m);
            return true;
        } else {
            return false;
        }
    }
    /** 
     *  是否是唯一字段查重
     *  @param $org_id
     *  @param $object_attr
     *  @param $data
     */
    public function is_unique($org_id,$data,$object_attr,$module){
        $func = __FUNCTION__;
        $this->is_method_exits($data, $module, $func, $org_id);
        foreach ($data as $k => $v) {
            foreach ($v as $kk => $vv) {
                if($object_attr[$kk]['is_unique']&&!empty($vv)){
                    $where[$object_attr[$kk]['fld_name']] = $vv;
                    $result = $this->db->from($k)->where($where)->get()->result_array();
                    if(count($result)>0){
                        return $object_attr[$kk]['label'];
                    }
                    unset($where);
                }
            }
        }

        return 1;
    }
    public function formatID($type_id,$obj_data,$OrgID,&$data){
        $this->load->model('admin/nextid', 'nextid');
        $IDformat = $this->nextid->NewFormatID($type_id, $obj_data, $OrgID);

        if (!empty($IDformat['FORMAT'])) {
            $arr = explode('.', $IDformat['ATTR_NAME']);
            $FormatID = $this->nextid->NewID(strtoupper($arr[0]).'_'.strtoupper($arr[1]).'_' . $type_id . '_' . date('Ymd'), $OrgID);
            $strarr = array(
                '{YYYY}' => date('Y'),
                '{MM}' => date('m'),
                '{DD}' => date('d'),
                '{0000}' => str_pad($FormatID, 4, "0", STR_PAD_LEFT),
            );

            $lead_userid = strtr($IDformat['FORMAT'], $strarr);
            $data[$IDformat['ATTR_NAME']] = $lead_userid;
        }
        return $lead_userid;
      }
    /**
     * 新增通用方法
     */
    public function pub_add($data, $module, $OrgID = 1, $UserID){
        $this->load->model('admin/nextid', 'nextid');

        $time = date("Y-m-d H:i:s");

        $type_id = empty($data[$module . '.TypeID'])?$data[$module . '.Type.ID']:$data[$module . '.TypeID'];
        
        $object_attr = $this->attr->object_attr($OrgID, $module); //查出对象对应属性信息
        $tbl_name = $this->GetAllTbl($OrgID, $module);
        foreach ($tbl_name as $k => $v) {
            $insert[$v] = array();
        }

        $obj_data = $this->obj->NameGetObj($OrgID, $module);
        $main_tbl = $obj_data['MAIN_TABLE'];
        $pk = $obj_data['KEY_ATTR_FLD'];

        //生成公式id
        $this->formatID($type_id,$obj_data,$OrgID,$data);
        
        //生成自增id
        $nextid = $this->nextid->NewID($main_tbl, $OrgID);
        $userdata = $this->user->SelectOne($OrgID, $UserID);

        $data[$module . '.CreatedByID'] = $UserID;
        $data[$module . '.CreatedTime'] = $time;
        $data[$module . '.ModifiedByID'] = $UserID;
        $data[$module . '.ModifiedTime'] = $time;


        if($module=='Account'){
          $data['Account.emstatus']='潜在客户';
          $data['Account.emstatus.value']='潜在客户';
          $data['Account.emstatus.enum_key']='1001';
        }
        //数据解析
        $ndata = $this->transform($data, $module, $OrgID);
        if ($ndata == false || empty($ndata)) {
            $res['res'] = 'fail';
            $res['msg'] = '保存失败！请填写正确所有者或部门！';
            return $res;
        }
        //去空&分表
        foreach ($ndata as $k => $v) {
            if (empty($v)) {
                continue;
            }
            $insert[$object_attr[$k]['tbl_name']][$k] = $v;
        }
        if($module == 'Task'){
            $insert['tc_activity']['Task.ActvtClass'] = $obj_data['OBJ_TYPE'];
        }
        //唯一值时查重
        $is_unique = $this->is_unique($org_id,$insert,$object_attr,$module);
        if($is_unique != 1){
            $res['res'] = 'fail';
            $res['msg'] = '保存失败！'.$is_unique.'重复，数据库中已存在！';
            return $res;
        }
        $res['obj_data'] = $obj_data;
        $res['nextid'] = $nextid;
        $res['pk'] = $pk;
        $res['object_attr'] = $object_attr;
        $res['ndata'] = $ndata;
        $res['insert'] = $insert;
        $res['res'] = 'suc';
        return $res;

    }

    /**
     * 新增方法
     * @param string $tbl_name
     * @param array  $tbl_data
     * @return int  返回自增长ID
     */
    public function _add($data, $module, $OrgID = 1, $UserID) {
        //新增通用处理
        $ret = $this->pub_add($data, $module, $OrgID = 1, $UserID);
        if($ret['res'] == 'fail'){
          return $ret;
        }
        //$this->db->trans_start();
        //新增执行层
        $this->trans_add_act($ret['insert'],$ret['object_attr'],$ret['pk'],$ret['nextid'],$OrgID,$module,$ret['ndata'],$ret['obj_data']);
        //$this->db->trans_complete();
        // if ($this->db->trans_status() === FALSE){
        //     $res['res'] = 'fail';
        //     $res['msg'] = '保存失败！';
        // }else{
            $res['res'] = 'suc';
            $res['id'] = $ret['nextid'];
            $res['msg'] = '保存成功！';
        //}
        return $res;
    }

    /**
     * 事务执行
     * @param $insert 分表的数组
     * @param $object_attr 对象所有属性
     * @param $pk 主键名
     * @param $nextid 自增id
     * @param $OrgID 组织id
     * @param $module 对象名
     * @param $ndata 解析完的数据（未分表）
     * @param $obj_data 对象信息
     */
    public function trans_add_act($insert,$object_attr,$pk,$nextid,$OrgID,$module,$ndata,$obj_data){
        foreach ($insert as $k => $v) {
            if (!empty($v)) {
                foreach ($v as $kk => $vv) {

                    switch ($object_attr[$kk]['attr_type']) {
                        case 4:
                            if($object_attr[$kk]['is_ref_obj']){
                                $this->db->set($object_attr[$object_attr[$kk]['referred_by']]['fld_name'], $vv);
                            }else{
                                $this->db->set($object_attr[$kk]['fld_name'], $vv);
                            }
                            break;
                        case 6://blob单独处理
                            break;
                        case 1:
                        case 2:
                        case 3:
                        case 11:
                        case 12:
                        case 13:
                        case 18:
                        case 19:
                        case 20:
                            $this->db->set($object_attr[$kk]['fld_name'], $vv, false);
                            break;
                        case 15:
                            if (!empty($vv)) {
                                //$vv = dataReduce8('Y-m-d', $vv);
                                $this->db->set($object_attr[$kk]['fld_name'], "to_date('" . $vv . "','yyyy-mm-dd hh24:mi:ss')", false);
                            }

                            break;
                        case 16:
                            if (!empty($vv)) {
                                $vv = dataReduce8('Y-m-d H:i:s', $vv);
                                $this->db->set($object_attr[$kk]['fld_name'], "to_date('" . $vv . "','yyyy-mm-dd hh24:mi:ss')", false);
                            }

                            break;
                        default:
                            $this->db->set($object_attr[$kk]['fld_name'], $vv);
                            break;
                    }
                }
            }

            $this->db->set($pk, $nextid);

            $this->db->set('org_id', $OrgID, false);
            $this->db->insert($k);
        }

        //处理blob

        foreach ($insert as $k => $v) {
            if (empty($v)) {
                continue;
            }
            foreach ($v as $kk => $vv) {
                switch ($object_attr[$kk]['attr_type']) {
                    case 6://blob
                    
                        $where = array($pk => $nextid, 'org_id' => $OrgID);
                        $set = array($object_attr[$kk]['fld_name'] => $vv);
                        $this->objects->_blob_update($k, $where, $set);
                        break;
                    default:
                        break;
                }
            }
        }

        //相关负责员工和管理维度
        $obj_name = strtolower($module);
        $rel_user = strtr('rel_xxx_user', 'xxx', $obj_name);
        $rel_privilege = strtr('rel_xxx_privilege', 'xxx', $obj_name);
        if($module == 'Task'){
            $rel_user_id = $ndata[$module . '.AssignedToID']?$ndata[$module . '.AssignedToID']:$ndata[$module . '.AssignedTo'];
            
            $this->savereluser($OrgID,10,$nextid,$rel_user_id,1);
        }else{
            $rel_user_id = $ndata[$module . '.OwnerID']?$ndata[$module . '.OwnerID']:$ndata[$module . '.Owner'];
            
            $this->savereluser($OrgID,$obj_data['OBJ_TYPE'],$nextid,$rel_user_id,1);
        }
        
        if($module=='Contact'){
            $rel_dept_id = $ndata[$module . '.OwnerDeptID']?$ndata[$module . '.OwnerDeptID']:$ndata[$module . '.OwnerDept'];
            
        }elseif($module == 'Task'){
            //没有相关管理维度
        }else{
            $rel_dept_id = $ndata[$module . '.DepartmentID']?$ndata[$module . '.DepartmentID']:$ndata[$module . '.Department'];
            
        }

        if($module != 'Task'){
            $this->saverelprivilege($OrgID,$obj_data['OBJ_TYPE'],$nextid,$rel_dept_id);
            
        }
        $this->remind->AddRemind($obj_data['OBJ_TYPE'],$OrgID,1,$nextid);
    }

    public function addrelactvt($org_id, $obj_name, $next_id, $pk, $pk_id,$user_id){
        $time = dataReduce8('Y-m-d H:i:s', date("Y-m-d H:i:s"));
        $insert = array(
            'org_id' => $org_id,
            'actvt_id' => $next_id,
            $pk => $pk_id,
            'create_user_id' => $user_id,
            );
        $this->db->set('create_time', "to_date('" . $time . "','yyyy-mm-dd hh24:mi:ss')", false);
        $this->db->insert('rel_activity_'.$obj_name, $insert);
    }

    public function addstage($org_id=1,$nextid){
        $this->db->from('tc_opportunity_stage');
        $where = array(
            'org_id' => $org_id,
            'obj_id' => 0,
            'type_id' => 2056
            );
        $this->db->where($where);
        $stage_data = $this->db->get()->result_array();
        $stage_data[0]['STAGE_ID'] = $this->nextid->NewID('tc_opportunity_stage',$org_id);
        $stage_data[0]['TYPE_ID'] = 0;
        $stage_data[0]['OBJ_ID'] = $nextid;
        $this->db->insert('tc_opportunity_stage',$stage_data[0]);
        return $stage_data[0]['STAGE_ID'];
    }
    /*相关员工、负责员工保存*/
    public function savereluser($OrgID=1,$obj_type,$id,$user_id,$is_self){
        $data = $this->obj->getOneData($obj_type);
        $where = array(
            'org_id' => $OrgID,
            $data['KEY_ATTR_FLD'] => $id,
            'user_id' => $user_id
            );
        $this->db->where($where);
        $this->db->from('rel_' . $data['OBJ_NAME'] . '_user');
        $check = $this->db->get()->result_array();
        if(!empty($check)){
            $res['res'] = 'fail';
            $res['msg'] = '选择的员工已加入相关员工或负责员工！';
            return $res;
        }
        $rel_user_arr = array(
            'org_id' => $OrgID,
            $data['KEY_ATTR_FLD'] => $id,
            'user_id' => $user_id,
            'is_self' => $is_self
            );
        $this->db->insert('rel_' . $data['OBJ_NAME'] . '_user', $rel_user_arr);
        $res['res'] = 'suc';
        return $res;
    }
    /*相关管理维度保存*/
    public function saverelprivilege($OrgID=1,$obj_type,$id,$dept_id){
        $data = $this->obj->getOneData($obj_type);
        $where = array(
            'org_id' => $OrgID,
            $data['KEY_ATTR_FLD'] =>$id,
            'privilege_type' => 95,
            'privilege_id' => $dept_id,
            'is_self' => 1
            );
        $this->db->where($where);
        $this->db->from('rel_' . $data['OBJ_NAME'] . '_privilege');
        $check = $this->db->get()->result_array();
        if(!empty($check)){
            $res['res'] = 'fail';
            $res['msg'] = '选择的部门已加入相关管理维度！';
            return $res;
        }
        $rel_privilege_arr = array(
            'org_id' => $OrgID,
            $data['KEY_ATTR_FLD'] =>$id,
            'p_id' => $this->nextid->NewID('rel_'.$data['OBJ_NAME'].'_privilege',$OrgID),
            'privilege_type' => 95,
            'privilege_id' => $dept_id,
            'is_self' => 1
            );
        $this->db->insert('rel_' . $data['OBJ_NAME'] . '_privilege', $rel_privilege_arr);
        $res['res'] = 'suc';
        return $res;
    }
    /*其他相关对象保存*/
    public function saverel($OrgID=1,$obj_type,$id,$rel_obj_type,$rel_id){
        $data = $this->obj->getOneData($obj_type);
        $rel_data = $this->obj->getOneData($rel_obj_type);
        $where = array(
            'org_id' => $OrgID,
            $data['KEY_ATTR_FLD'] => $id,
            $rel_data['KEY_ATTR_FLD'] => $rel_id,
            );
        $this->db->where($where);
        $this->db->from('rel_' . $data['OBJ_NAME'] . '_'.$rel_data['OBJ_NAME']);
        $check = $this->db->get()->result_array();
        if(!empty($check)){
            $res['res'] = 'fail';
            $res['msg'] = '关系已经存在！请选择其他。';
            return $res;
        }
        $rel_arr = array(
            'org_id' => $OrgID,
            $data['KEY_ATTR_FLD'] => $id,
            $rel_data['KEY_ATTR_FLD'] => $rel_id,
            );
        $ret = $this->db->insert('rel_' . $data['OBJ_NAME'] . '_'.$rel_data['OBJ_NAME'], $rel_arr);
        $res['res'] = 'suc';
        return $res;
    }
    /**
     * 更新数据方法
     * @param string $tbl_name  对象名
     * @param array $tbl_data   要更新的数据
     * @param array $tbl_where  要更新数据的条件
     * @return boolean  返回执行是否成功
     */
    public function _update($data, $where, $object_attr, $module, $OrgID = 1, $UserID,$content=array()) {
        //获取权限
        $objData = $this->obj->NameGetObj($OrgID, $module);
        

        $time = date("Y-m-d H:i:s");
        $obj_data = $this->obj->NameGetObj($OrgID, $module);

        $main_tbl = $obj_data['MAIN_TABLE'];

        $data[$module . '.ModifiedByID'] = $UserID;
        $data[$module . '.ModifiedTime'] = $time;
        // foreach ($data as $k => $v) {
        //     if ($object_attr[$k]['is_must'] && empty($data[$k])) {
        //         $res['res'] = "fail";
        //         $res['error_code'] = "201";
        //         $res['msg'] = "缺少\"" . $k . "\"参数，保存失败！";
        //         echo "\"" . $object_attr[$k]['label'] . "\"未填，保存失败！";
        //         return $res;
        //     }
        // }

        $ndata = $this->transform($data, $module, $OrgID);
        foreach ($ndata as $k => $v) {
            if(empty($v)){
                $update[$object_attr[$k]['tbl_name']][$k]="";
            }else{
                $update[$object_attr[$k]['tbl_name']][$k] = $v;
            }
            
        }

        // $update[$main_tbl]['Lead.ModifiedByID'] = $this->session->userdata('user_id');
        // $update[$main_tbl]['Lead.ModifiedTime'] = $time;
        //$this->db->trans_start();
        foreach ($update as $k => $v) {

            foreach ($v as $kk => $vv) {

                switch ($object_attr[$kk]['attr_type']) {

                    case 6://blob单独处理
                        break 2;
                    case 1:
                    case 2:
                    case 3:
                    case 11:
                    case 12:
                    case 13:
                    case 18:
                    case 19:
                    case 20:

                        // if(!is_int($vv)){
                        //     $res['res'] = "fail";
                        //     $res['error_code'] = "204";
                        //     $res['msg'] = "\"".$kk."\"必须是数值!";
                        //     return $res;
                        // }
                        if(empty($vv)){
                            $this->db->set($object_attr[$kk]['fld_name'], $vv);
                        }else{
                            $this->db->set($object_attr[$kk]['fld_name'], $vv, false);
                          
                        }
                        break;
                    case 15:
                        if (!empty($vv)) {
                            //$vv = dataReduce8('Y-m-d', $vv);
                            $this->db->set($object_attr[$kk]['fld_name'], "to_date('" . $vv . "','yyyy-mm-dd hh24:mi:ss')", false);
                        }
                        break;
                    case 16:
                        if (!empty($vv)) {
                            $vv = dataReduce8('Y-m-d H:i:s', $vv);
                            $this->db->set($object_attr[$kk]['fld_name'], "to_date('" . $vv . "','yyyy-mm-dd hh24:mi:ss')", false);
                        }
                        break;
                    case 18:

                        $this->db->set($object_attr[$kk]['fld_name'], $vv, false);
                        break;
                    default:

                        $this->db->set($object_attr[$kk]['fld_name'], $vv);
                        break;
                }
            }

            $this->db->where($where);
            if($module != 'User'){
                $pwhere = $this->premissionList2($objData, $k, $OrgID, $UserID, "edit");
                $this->db->where($pwhere, NULL, FALSE);
            }
            $this->db->update($k);

        }
        //处理blob
        foreach ($update as $k => $v) {
            foreach ($v as $kk => $vv) {
                switch ($object_attr[$kk]['attr_type']) {
                    case 6://blob
                        $set = array($object_attr[$kk]['fld_name'] => $vv);
                        $this->objects->_blob_update($k, $where, $set);
                        //$this->db->set($object_attr[$kk]['fld_name'], "utl_raw.cast_to_raw('".base64_encode($vv)."')",false);
                        break;
                    default:
                        break;
                }
            }
        }
        if(isset($ndata[$module . '.AssignedToID']) || isset($ndata[$module . '.OwnerID'])||isset($ndata[$module . '.AssignedTo'])||isset($ndata[$module . '.Owner'])){
            if($module == 'Task'){
                $du_where = array(
                    'org_id' => $OrgID,
                    $obj_data['KEY_ATTR_FLD'] => $where[$obj_data['KEY_ATTR_FLD']],
                    'user_id' => $content[$module . '.AssignedToID']?$content[$module . '.AssignedToID']:$content[$module . '.AssignedTo'],
                    'is_self'=> 1
                  );
                $this->db->where($du_where)->delete('rel_' . $module . '_user');
                
                $rel_user_id = $ndata[$module . '.AssignedToID']?$ndata[$module . '.AssignedToID']:$ndata[$module . '.AssignedTo'];
                
                $this->savereluser($OrgID,10,$where[$obj_data['KEY_ATTR_FLD']],$rel_user_id,1);
            }else{
                $du_where = array(
                    'org_id' => $OrgID,
                    $obj_data['KEY_ATTR_FLD'] => $where[$obj_data['KEY_ATTR_FLD']],
                    'user_id' => $content[$module . '.OwnerID']?$content[$module . '.OwnerID']:$content[$module . '.Owner'],
                    'is_self'=> 1
                  );
                $this->db->where($du_where)->delete('rel_' . $module . '_user');
                $rel_user_id = $ndata[$module . '.OwnerID']?$ndata[$module . '.OwnerID']:$ndata[$module . '.Owner'];
                
                $this->savereluser($OrgID,$obj_data['OBJ_TYPE'],$where[$obj_data['KEY_ATTR_FLD']],$rel_user_id,1);
            }
            
            if($module=='Contact'){
                $dp_where = array(
                    'org_id'=>$OrgID,
                    'privilege_id' => $content[$module . '.OwnerDeptID']?$content[$module . '.OwnerDeptID']:$content[$module . '.OwnerDept'],
                    $obj_data['KEY_ATTR_FLD']=>$where[$obj_data['KEY_ATTR_FLD']]
                  );
                $this->db->where($dp_where)->delete('rel_' . $module . '_privilege');
                $rel_dept_id = $ndata[$module . '.OwnerDeptID']?$ndata[$module . '.OwnerDeptID']:$ndata[$module . '.OwnerDept'];
                
            }elseif($module == 'Task'){
                //没有相关管理维度
            }else{
                $dp_where = array(
                    'org_id'=>$OrgID,
                    'privilege_id' => $content[$module . '.DepartmentID']?$content[$module . '.DepartmentID']:$content[$module . '.Department'],
                    $obj_data['KEY_ATTR_FLD']=>$where[$obj_data['KEY_ATTR_FLD']]
                  );
                $this->db->where($dp_where)->delete('rel_' . $module . '_privilege');
                $rel_dept_id = $ndata[$module . '.DepartmentID']?$ndata[$module . '.DepartmentID']:$ndata[$module . '.Department'];
                
            }

            if($module != 'Task'){
                $this->saverelprivilege($OrgID,$obj_data['OBJ_TYPE'],$where[$obj_data['KEY_ATTR_FLD']],$rel_dept_id);
                
            }

        }

        $this->remind->AddRemind($obj_data['OBJ_TYPE'],$OrgID,2,$where[$obj_data['KEY_ATTR_FLD']]);
        //$this->db->trans_complete();
        // if ($this->db->trans_status() === FALSE){
        //     return false;
        // }else{

        $res['res'] = 'suc';
        $res['msg'] = '更新成功！';
        return $res;
        // }
    }
    public function _detail_update($data, $where, $object_attr, $module, $OrgID = 1, $UserID) {
        //获取权限

        $objData = $this->obj->NameGetObj($OrgID, $module);
        $pwhere = $this->premissionList($objData, $OrgID, $UserID, "edit");

        $time = date("Y-m-d H:i:s");
        $obj_data = $this->obj->NameGetObj($OrgID, $module);

        $main_tbl = $obj_data['MAIN_TABLE'];


        // foreach ($data as $k => $v) {
        //     if ($object_attr[$k]['is_must'] && empty($data[$k])) {
        //         $res['res'] = "fail";
        //         $res['error_code'] = "201";
        //         $res['msg'] = "缺少\"" . $k . "\"参数，保存失败！";
        //         echo "\"" . $object_attr[$k]['label'] . "\"未填，保存失败！";
        //         return $res;
        //     }
        // }

        $ndata = $this->transform($data, $module, $OrgID);

        foreach ($ndata as $k => $v) {
            $update[$object_attr[$k]['tbl_name']][$k] = $v;
        }

        // $update[$main_tbl]['Lead.ModifiedByID'] = $this->session->userdata('user_id');
        // $update[$main_tbl]['Lead.ModifiedTime'] = $time;
        //$this->db->trans_start();
        foreach ($update as $k => $v) {

            foreach ($v as $kk => $vv) {

                switch ($object_attr[$kk]['attr_type']) {

                    case 6://blob单独处理
                        break 2;
                    case 1:
                    case 2:
                    case 3:
                    case 11:
                    case 12:
                    case 13:
                    case 18:
                    case 19:
                    case 20:

                        // if(!is_int($vv)){
                        //     $res['res'] = "fail";
                        //     $res['error_code'] = "204";
                        //     $res['msg'] = "\"".$kk."\"必须是数值!";
                        //     return $res;
                        // }

                        $this->db->set($object_attr[$kk]['fld_name'], $vv, false);
                        break;
                    case 15:
                        if (!empty($vv)) {
                            $vv = dataReduce8('Y-m-d', $vv);
                            $this->db->set($object_attr[$kk]['fld_name'], "to_date('" . $vv . "','yyyy-mm-dd hh24:mi:ss')", false);
                        }
                        break;
                    case 16:
                        if (!empty($vv)) {
                            $vv = dataReduce8('Y-m-d H:i:s', $vv);
                            $this->db->set($object_attr[$kk]['fld_name'], "to_date('" . $vv . "','yyyy-mm-dd hh24:mi:ss')", false);
                        }
                        break;
                    case 18:

                        $this->db->set($object_attr[$kk]['fld_name'], $vv, false);
                        break;
                    default:

                        $this->db->set($object_attr[$kk]['fld_name'], $vv);
                        break;
                }
            }

            $this->db->where($where);
            $this->db->where($pwhere, NULL, FALSE);
            $this->db->update($k);

        }
        //$this->db->trans_complete();
        // if ($this->db->trans_status() === FALSE){
        //     return false;
        // }else{
        $res['res'] = 'suc';
        $res['msg'] = '更新成功！';
        return $res;
        // }
    }
    /**
     *删除时判断对象有没有被其他对象引用
     */
    public function is_reference($obj_name,$id){
        $select = array(
            'b.main_table',
            'b.key_attr_fld'
        );
        $condition = array(
            'a.ref_obj_name' => $obj_name,
            'a.org_id' => $this->session->userdata('org_id'),
            'b.org_id' => $this->session->userdata('org_id'),

        );
        $query = $this->db->from("dd_attribute a")->
            join('dd_object b', 'a.obj_name=b.obj_name', 'left')->
            where($condition)->
            select($select)->get()->result_array();

        foreach($query as $value){
            $condition = null;
            $condition[$value["KEY_ATTR_FLD"]] = $id;
            $query = $this -> db-> from($value["MAIN_TABLE"]) -> where($condition)-> count_all_results();
           if($query > 0){
               return true;
           }
        }
        return false;

    }
    /**
     * 	delete				公共的删除方法
     * 	@params	int	  $lead_id	要删除数据的编号
     * 	@parmas	int		$obj_type 删除表得到编号
     * 	@return	bool
     */
    public function _del($id, $obj_type, $data, $pwhere, $main_type = 0) {

        $obj = $this->obj->getOneData($obj_type);
        $obj_id = $obj['KEY_ATTR_FLD'];
        $condition[$obj_id] = $id;
        $table_name = $this->getDeleteTable($obj_type);
        if($table_name){
            if(strpos($table_name,"_xxx_")){
                $main_obj =  $this->obj->getOneData($main_type);
                $main_obj_name= $main_obj["OBJ_NAME"];
                $table_name = strtolower(str_replace("xxx",$main_obj_name,$table_name) );
                return $this->db->where($condition)->where($pwhere)->delete($table_name);
            }else{
                $obj_name = $obj["OBJ_NAME"];
                if( $this -> is_recyclable($obj_name) ){
                    foreach ($data as $k => $v) {
                        if ($k == "DELETED_TIME") {
                            $this->db->set($k, $v, false);
                        } else {
                            $this->db->set($k, $v);
                        }
                    }
                    return $this->db->where($condition)->where($pwhere)->update($table_name);
                }else{
                    return $this->db->where($condition)->where($pwhere)->delete($table_name);
                }
            }
        }else{
            return false;
        }

    }

    /**
     *相关对象删除特殊处理
     */
    public function related_objects($id, $mid,$obj_type, $data, $pwhere, $main_type ){
        $obj = $this->obj->getOneData($obj_type);
        $main_obj =  $this->obj->getOneData($main_type);
        $main_obj_name= $main_obj["OBJ_NAME"];
        $table_name = strtolower("rel_".$main_obj_name."_user");
        $condition[$obj['KEY_ATTR_FLD']] = $id;
        $condition[$main_obj['KEY_ATTR_FLD']] = $mid;
        return $this->db->where($condition)->where($pwhere)->delete($table_name);
    }


    /**
     * 查询对象是不是物理删除还是逻辑删除
     * @param $obj_name   对象名称
     * @return mixed
     */
    public function is_recyclable($obj_name){
        $select = array(
            'is_recyclable ',
        );
        $condition = array(
            'org_id' => $this->session->userdata('org_id'),
            'obj_name' => $obj_name
        );
        $query = $this->db -> select($select)->where($condition)->get("dd_object")->result_array();
        return $query[0]['IS_RECYCLABLE'];

    }



    /**
     * 	getDeleteTable				根据表的编号获取相应的表名
     * 	@params	int	  $obj_type	表的编号
     * 	@return	string 表的名字
     */
    private function getDeleteTable($obj_type) {
        $this->db->select("MAIN_TABLE");
        $this->db->from("dd_object");
        $this->db->where('OBJ_TYPE', $obj_type);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['MAIN_TABLE'];
        } else {
            return null;
        }
    }

    /**
     * 历史变动数据
     * @param array $data   要更新的数据
     * @param     $obj_id  对象id（eg：线索id）
     * @param array $content 老数据
     * @param string $object_attr  属性数据
     * @param string $module  对象名
     * @return array  历史变动数据
     */
    public function history($data, $obj_id, $content, $object_attr, $module, $user_id) {
        $this->load->model('admin/nextid', 'nextid');
        $org_id = $this->session->userdata('org_id');
        $org_id = $org_id ? $org_id : 1;
        $table = 'tc_' . $module . '_history';
        $obj_data = $this->obj->NameGetObj($org_id, $module);
        foreach ($data as $k => $v) {
            if ($object_attr[$k]['is_history']) {
                if (isset($content[$k . '.enum_key'])) {
                    $old_value = $content[$k . '.enum_key'];
                } else {
                    $old_value = $content[$k];
                }
                
                $nextid = $this->nextid->NewId($table, $org_id);
                $time = dataReduce8('Y-m-d H:i:s', date("Y-m-d H:i:s"));
                $insert[] = array(
                    'his_id' => $nextid,
                    'org_id' => $org_id,
                    $obj_data['KEY_ATTR_FLD'] => $obj_id,
                    'attr_name' => $k,
                    'old_value' => $old_value,
                    'new_value' => $v,
                    'create_user_id' => $user_id,
                    'create_time' => "to_date('$time','yyyy-mm-dd hh24:mi:ss')",
                );
            }
        }
        if (!empty($insert)) {
            foreach ($insert as $k => $v) {
                foreach ($v as $key => $value) {
                    if ($key == 'create_time') {
                        $this->db->set($key, $value, false);
                    } else {
                        $this->db->set($key, $value);
                    }
                }
                $this->db->insert($table);
            }
        }
        return true;
    }

    /**
     * GetOneContent 封装的获取一条对象内容
     * @param $ObjName      对象名称
     * @param $OrgID        组织id
     * @param $ObjID        对象id
     * return array $data
     */
    public function GetOneContent($ObjName, $OrgID, $ObjID) {
        $ObjAttr = $this->attr->object_attr($OrgID, $ObjName);
        $data = $this->GetOldInfo($ObjName, $OrgID, $ObjAttr, $ObjID);
        return $data;
    }

    /**
     * GetOldInfo 查出对象所有内容
     * @param $ObjName    对象名
     * @param $OrgID  组织id
     * @param $ObjAttr   对象所有属性
     * @param $ObjID  对象id
     * return array $lead_data
     */
    public function GetOldInfo($ObjName, $OrgID, $ObjAttr, $ObjID) {
        if ($ObjID) {
            $content = $this->GetAll($OrgID, $ObjID, $ObjName);
        }

        foreach ($ObjAttr as $k => $v) {

            switch ($v['attr_type']) {
                case 4://是否为引用
                    if ($v['is_ref_obj']) {
                        //对象查到表
                        if ($v["ref_obj_name"] == "ReferObject") {
                            $SRC_ID = $content[strtoupper($ObjAttr[$v["referred_by"]]["fld_name"])];
                            if ($SRC_ID == "0" || empty($SRC_ID)) {
                                $lead_data[$k] = "";
                                break;
                            } else {
                                /**
                                 *  ReferObject特殊处理
                                 *  1 得到值 然后根据对象查询到 得到值的类型 例如对象名为account 查询account表根据src_id得到src_type
                                 *  2 由1得出dd_object的值 再根据SRC_TYPE 从dd_object取出obj_name值
                                 */
                                $lrot = array('org_id'=>$OrgID,'attr_name'=>$k);
                                $lrotdata = $this->db->select('lee_refer_obj_type as lrot')->from('dd_attribute')->where($lrot)->get()->result_array();
                                if(empty($lrotdata[0]['LROT'])){
                                    $ff = strtoupper($ObjAttr[$k . "Type"]['fld_name']);
                                }else{
                                    $ff = strtoupper($ObjAttr[$lrotdata[0]['LROT']]['fld_name']);
                                }
                                
                                $obj_data = $this->obj->getOneData($content[$ff]);
                            }
                        } else {
                            //对象查到表
                            $RefObjName = $v["ref_obj_name"]; //取obj_name名称
                            $obj_data = $this->obj->NameGetObj($OrgID, $RefObjName);
                        }


                        //属性名转换为字段名
                        $AttrBean["select"] = "TBL_NAME,FLD_NAME,OBJ_NAME"; //字段
                        $AttrBean["where"]["attr_name"] = $obj_data["NAME_ATTR_NAME"];
                        $AttrBean["where"]["org_id"] = $OrgID;
                        $attr_data = $this->attr->SelectOne($AttrBean);

                        //查出值
                        $fld_name = strtoupper($ObjAttr[$v['referred_by']]['fld_name']);

                        if (isset($content)) {
                            // $module = "www/" . $attr_data["OBJ_NAME"] . "_model";
                            // $this->load->model($module, $RefObjName);
                            // $r_data = $this->$RefObjName->SelectOne($OrgID, $content[$fld_name]);
                            $where = array(
                                $obj_data["KEY_ATTR_FLD"] => $content[$fld_name],
                                "org_id" => $OrgID,
                            );
                            $select = array(
                                $attr_data["FLD_NAME"],
                            );
                            $this->db->select($select);
                            $this->db->where($where);
                            $this->db->from($attr_data["TBL_NAME"]);
                            $r_data = $this->db->get()->result_array();

                            $lead_data[$k] = $r_data[0][strtoupper($attr_data["FLD_NAME"])];

                            $lead_data[$k . '.ID'] = $content[$fld_name];
                            $lead_data[$k . '.Name'] = $r_data[0][strtoupper($attr_data["FLD_NAME"])];
                            $ObjAttr[$k]['content'] = $r_data;
                        } else {
                            $lead_data[$k] = "";
                        }
                    } else {
                        $fld_name = strtoupper($v['fld_name']);
                        if (!empty($fld_name)) {
                            if (isset($content)) {
                                if (is_object($content[$fld_name])) {
                                    $content[$fld_name] = base64_decode($content[$fld_name]->load());
                                }
                                $lead_data[$k] = $content[$fld_name];
                            } else {
                                $lead_data[$k] = "";
                            }
                        } else {
                            $lead_data[$k] = "";
                        }
                    }
                    break;
                case 12:
                    $AttrBean["select"] = "ENUM_ATTR_NAME"; //字段
                    $AttrBean["where"]["attr_name"] = $k;
                    $AttrBean["where"]["org_id"] = $OrgID;
                    $attr_data = $this->attr->SelectOne($AttrBean);
                    $LangId = 2;
                    $AttrName = empty($attr_data["ENUM_ATTR_NAME"]) ? $k : $attr_data["ENUM_ATTR_NAME"];
                    $fld_name = strtoupper($v['fld_name']);
                    if (!empty($content[0][$fld_name])) {

                        $enum = $this->enum->SelectOneStr($OrgID, $LangId, $AttrName, $content[$fld_name]);

                        $lead_data[$k] = $enum['ENUM_VALUE'];
                        $lead_data[$k . '.enum_key'] = $content[$fld_name];
                        $lead_data[$k . '.value'] = $enum['ENUM_VALUE'];
                    } else {
                        $lead_data[$k] = "";
                        $lead_data[$k . '.enum_key'] = "";
                        $lead_data[$k . '.value'] = "";
                    }

                    break;
                case 13://下拉单选查出枚举值
                    $AttrBean["select"] = "ENUM_ATTR_NAME"; //字段
                    $AttrBean["where"]["attr_name"] = $k;
                    $AttrBean["where"]["org_id"] = $OrgID;
                    $attr_data = $this->attr->SelectOne($AttrBean);
                    $LangId = 2;
                    $AttrName = empty($attr_data["ENUM_ATTR_NAME"]) ? $k : $attr_data["ENUM_ATTR_NAME"];
                    $fld_name = strtoupper($v['fld_name']);
                    if (!empty($content[$fld_name])) {

                        $enum = $this->enum->SelectOneStr($OrgID, $LangId, $AttrName, $content[$fld_name]);

                        $lead_data[$k] = $enum['ENUM_VALUE'];
                        $lead_data[$k . '.enum_key'] = $content[$fld_name];
                        //$lead_data[$k.'.enum_attr_name'] = $AttrName;
                        $lead_data[$k . '.value'] = $enum['ENUM_VALUE'];
                    } else {
                        $lead_data[$k] = "";
                        $lead_data[$k . '.enum_key'] = "";
                        //$lead_data[$k.'.enum_attr_name'] = $AttrName;
                        $lead_data[$k . '.value'] = "";
                    }
                    break;
                case 14:
                    break;
                case 15://日期型
                    $fld_name = strtoupper($v['fld_name']);
                    if (isset($content)) {
                        $content[$fld_name] = $this->ToChar($fld_name, $v['tbl_name'], $OrgID, $ObjID, $ObjName);
                        if (empty($content[$fld_name])) {
                            $lead_data[$k] = "";
                        } else {
                            $lead_data[$k] = $this->isDate($content[$fld_name], 8 * 60 * 60);
                        }
                    } else {
                        $lead_data[$k] = "";
                    }
                    break;
                case 16://时间型
                    $fld_name = strtoupper($v['fld_name']);
                    if (isset($content)) {
                        $content[$fld_name] = $this->ToChar($fld_name, $v['tbl_name'], $OrgID, $ObjID, $ObjName);
                        if (empty($content[$fld_name])) {
                            $lead_data[$k] = "";
                        } else {
                            $lead_data[$k] = $this->isDateTime($content[$fld_name], 8 * 60 * 60);
                        }
                    } else {
                        $lead_data[$k] = "";
                    }
                    break;
                default:
                    $fld_name = strtoupper($v['fld_name']);
                    if (!empty($fld_name)) {
                        if (isset($content)) {
                            if (is_object($content[$fld_name])) {

                                $content[$fld_name] = base64_decode($content[$fld_name]->load());
                            }
                            $lead_data[$k] = $content[$fld_name];
                        } else {
                            $lead_data[$k] = "";
                        }
                    } else {
                        $lead_data[$k] = "";
                    }
                    break;
            }
        }
        return $lead_data;
    }

    /**
     * GetImportData   取得导入 .csv并转化
     * @param $obj_type    对象类型
     * @param $file    .csv文件
     * @param $data    传入的数据
     * return array $data_arr
     */
    public function GetImportCSV($obj_type, $file, $data) {
        $org_id = $this->session->userdata('$org_id');
        $symbol = $data['symbol'];
        $with_title = $data['with_title'];
        $encode = $_FILES["myfile"]["encode"];
        $file_data = fgetcsv($file, 1024 * 10, $symbol);
        if ($encode != 'UTF-8') {
            mb_convert_encoding($file_data, "UTF-8", $encode);
        }
        $obj_data = $this->obj->getOneData($obj_type);
        $object_attr = $this->attr->object_attr($org_id, $obj_data['OBJ_NAME']);

        foreach ($file_data[0] as $k => $v) {
            $up_data[$k] = $k . ':' . $v;
        }

        foreach ($object_attr as $k => $v) {
            if ($v['is_disp_list']) {
                switch ($v['attr_type']) {
                    case '4':
                    case '5':
                    case '7':
                    case '8':
                    case '10':
                        $scr_data[$k]['is_key'] = 1;
                        break;

                    default:
                        $scr_data[$k]['is_key'] = 0;
                        break;
                }
                if ($v['is_ref_obj']) {
                    $obj_attr = $this->attr->object_attr($org_id, $v['ref_obj_name']);
                    foreach ($obj_attr as $kk => $vv) {
                        if ($vv['is_disp_list']) {
                            $scr_data[$k]['label'] = $v['label'];
                            $scr_data[$k][$kk] = $v['label'] . $vv['label'];
                        }
                    }
                } else {
                    $scr_data[$k]['label'] = $v['label'];
                }
            }
        }
        $data_arr['up_data'] = $up_data;
        $data_arr['scr_data'] = $scr_data;
        $data_arr['with_title'] = $with_title;
        $data_arr['create_enum'] = $create_enum;
        return $data_arr;
    }

    /**
     * ExportCSV  转化数据并导出 .csv
     * @param $data  需要导出的数据
     * @param $LeadID   线索id
     * @param $is_edit  是否为编辑页面
     * return array $lead_data
     */
    public function ExportCSV($data) {
        extract($data, EXTR_OVERWRITE);

        $org_id = $this->session->userdata('org_id');
        $title = "";
        $record = "";
        ///array_shift($ColNames[0]);
        $title = implode(",", $ColNames);

        foreach ($need_data as $k => $v) {

            $line = implode(",", $v);

            $record = $record . "\n" . $line;
        }

        $r_data['file_data'] = $title . $record;

        $r_data['file_name'] = 'exp' . chr(rand(65, 90)) . rand(100, 999) . '.csv';

        return $r_data;
    }

    /**
     * Import     导入数据库
     * @param $obj_type    对象类型
     * @param $data    传入的数据
     * return array $data_arr
     */
    public function module_import($data) {
        extract($data, EXTR_OVERWRITE);
        $is_deal = $post['is_deal']; //隐藏文本框
        $with_title = $post['with_title']; //隐藏文本框
        $create_enum = $post['create_enum']; //隐藏文本框
        $org_id = $this->session->userdata('org_id');
        unset($post['is_deal']);
        unset($post['with_title']);
        unset($post['create_enum']);

        $obj_data = $this->obj->getOneData($obj_type);
        $object_attr = $this->attr->object_attr($org_id, $obj_data['OBJ_NAME']);

        $list = $post;
        $file_data = $post['fld_data'];
        $list = array(
            'attr_name' => array(
                'attr_name' => '列号', //eg:1-7
                'is_checked_key' => 1,
            ),
        );
        foreach ($list as $k => $v) {
            if ($is_deal) {
                if ($v['is_checked_key']) {
                    //if()
                    $insert[1][$k] = $file_data[1];
                }
            }
        }
    }

    /**
     * transform  一维数组转换成能写入表的数据
     * array $data 一维数组
     * array 
     */
    public function transform($data, $obj_name, $OrgID = 1) {

        $ref_attr = $this->GetRefAttr($obj_name, $OrgID);
        //循环引用找id
        foreach ($ref_attr as $k => $v) {
            if (isset($data[$v['ATTR_NAME'] . "ID"])) {

                $data[$v['REFERRED_BY']] = $data[$v['ATTR_NAME'] . "ID"];
                unset($data[$v['ATTR_NAME'] . ".ID"]);
                unset($data[$v['ATTR_NAME']]);
                unset($data[$v['ATTR_NAME'].".Name"]);
            } elseif (isset($data[$v['ATTR_NAME'] . ".ID"])) {
                $data[$v['REFERRED_BY']] = $data[$v['ATTR_NAME'] . ".ID"];
                unset($data[$v['ATTR_NAME'] . ".ID"]);
                unset($data[$v['ATTR_NAME']]);
                unset($data[$v['ATTR_NAME'].".Name"]);
            } else {//找不到->中文找id
                $ref_obj_attr = $this->attr->object_attr($OrgID, $v['REF_OBJ_NAME']);

                foreach ($ref_obj_attr as $kk => $vv) {

                    $arr = explode(".", $kk, 2);
                    if (isset($data[$v['REFERRED_BY']])) {
                        unset($data[$v['ATTR_NAME'] . '.' . $arr[1]]);
                    } else {

                        if (isset($data[$v['ATTR_NAME'] . '.' . $arr[1]])) {

                            $ref_id = $this->Name2ID($v['REF_OBJ_NAME'], $vv, $data[$v['ATTR_NAME'] . '.' . $arr[1]], $OrgID);

                            if (count($ref_id) > 1 or count($ref_id) == 0) {
                                return false;
                            } elseif (count($ref_id) == 1) {
                                $data[$v['REFERRED_BY']] = $ref_id[0]['ID'];
                            }
                            unset($data[$v['ATTR_NAME'] . '.' . $arr[1]]);
                        }
                    }
                }
            }
        }
        if($obj_name == 'Task'){
            $user_id = $data[$obj_name . '.AssignedToID'];
        }else{
            $user_id = $data[$obj_name . '.OwnerID'];
        }
        if(!empty($user_id)){
          
            $u_where = array(
                'org_id' => $OrgID,
                'user_id' => $user_id
                );
            $dept_id = $this->db->select('dept_id')->from('tc_user')->where($u_where)->get()->result_array();
            if(count($dept_id)>1 || count($dept_id)==0){
                return false;
            }else{
                if($obj_name=='Contact'){
                    $data[$obj_name . '.OwnerDeptID'] = $dept_id[0]['DEPT_ID'];
                    
                }elseif($obj_name == 'Task'){
                    //没有相关管理维度
                }else{
                    $data[$obj_name . '.DepartmentID'] = $dept_id[0]['DEPT_ID']; 
                }
            }
        }else{
          unset($data[$obj_name . '.OwnerDeptID']);
          unset($data[$obj_name . '.DepartmentID']);
        }
        $enum_attr = $this->GetEnumAttr($obj_name, $OrgID);

        //循环枚举找key
        foreach ($enum_attr as $k => $v) {
            if (isset($data[$v['ATTR_NAME']])) {

                if (isset($data[$v['ATTR_NAME'] . ".enum_key"])) {

                    $data[$v['ATTR_NAME']] = $data[$v['ATTR_NAME'] . ".enum_key"];
                    unset($data[$v['ATTR_NAME'] . ".enum_key"]);
                    unset($data[$v['ATTR_NAME'] . ".value"]);
                } elseif (isset($data[$v['ATTR_NAME'] . ".value"])) {//找不到->中文找key
                    $enum_key = $this->Name2Key($v['ATTR_NAME'], $data[$v['ATTR_NAME'] . ".value"], $OrgID);
                    if (count($enum_key) > 1) {
                        return false;
                    } elseif (count($enum_key) == 1) {
                        $data[$v['ATTR_NAME']] = $enum_key[0]['ENUM_KEY'];
                    } elseif (count($enum_key) == 0) {
                        //return false;
                        $data[$v['ATTR_NAME']] = "";
                    }
                    unset($data[$v['ATTR_NAME'] . ".value"]);
                }
            }
        }

        return $data;
    }

    //获取对象所有引用属性
    public function GetRefAttr($obj_name, $org_id = 1) {
        $where = array(
            'obj_name' => $obj_name,
            'org_id' => $org_id,
            'is_ref_obj' => 1,
        );
        $this->db->where($where);
        $this->db->select('attr_name,referred_by,ref_obj_name');
        $this->db->from('dd_attribute');
        $data = $this->db->get()->result_array();
        return $data;
    }

    //获取对象所有枚举属性
    private function GetEnumAttr($obj_name, $org_id = 1) {
        $this->db->where('org_id', $org_id);
        $this->db->where('obj_name', $obj_name);
        $key_array = array('12', '13');
        $this->db->where_in('attr_type', $key_array);
        $this->db->select('attr_name,tbl_name,fld_name');
        $this->db->from('dd_attribute');
        $data = $this->db->get()->result_array();
        return $data;
    }

    //引用值转换id
    public function Name2ID($obj_name, $attr, $value, $org_id = 1) {

        $obj_data = $this->obj->NameGetObj($org_id, $obj_name);

        $where = array(
            'org_id' => $org_id,
            $attr['fld_name'] => $value,
        );
        $this->db->where($where);
        $this->db->select($obj_data['KEY_ATTR_FLD'] . " as ID");
        $this->db->from($attr['tbl_name']);
        $data = $this->db->get()->result_array();
        return $data;
    }

    //枚举值转换key
    public function Name2Key($attr_name, $enum_value, $org_id = 1) {

        $lang_id = $this->session->userdata('lang_id');
        $where = array(
            'org_id' => $org_id,
            'lang_id' => $lang_id,
            'attr_name' => $attr_name,
            'enum_value' => $enum_value,
        );
        $this->db->select('enum_key');
        $this->db->where($where);
        $this->db->from('tc_enum_str');
        $data = $this->db->get()->result_array();

        return $data;
    }

    /**
     * GetListColNames2 获取列表的ColName 属性名=>中文标签
     * @param array $ListFormat 列表布局
     * @param array $LeadAttr 线索的所有属性信息
     * @return array res_data 右侧已选字段 eg：1=>{attr_name=>属性名,labels=>中文标签}
     */
    public function GetListColNames2($ListFormat, $LeadAttr, $obj_data) {
        $res_data = array();
        $i = 0;
        foreach ($ListFormat as $k => $v) {

            $res_data[$i]['attr_name'] = $v;
            $res_data[$i]['label'] = $LeadAttr[$v]["label"];
            $i++;
        }
        return $res_data;
    }

    /**
     * GetLeftList 左侧待选字段（用于导出时选择字段，除右侧重复的字段，过滤掉不在页面显示的属性）
     * @param array $lead_attr 所有字段
     * @param array $labels  列表显示字段
     * @param array $left_list 已去重左侧字段list eg：1=>{attr_name=>属性名,labels=>中文标签}
     */
    public function GetLeftList($object_attr, $labels) {


        foreach ($labels as $k => $v) {
            $r_labels[$v['attr_name']] = $v['label'];
        }

        foreach ($object_attr as $k => $v) {


            if (!isset($r_labels[$k]) && !$v['is_hidden'] && $v['is_disp_list']) {
                $left_list[$v['disp_order']]['attr_name'] = $k;
                $left_list[$v['disp_order']]['label'] = $v['label'];
            }
        }

        ksort($left_list);
        return $left_list;
    }

    private function GetFld2($NeedAttr, $LeadAttr) {
        $res_data = array(); //要返回的数组信息
        $fld_que = "";//存储每一次的字段名
        foreach ($NeedAttr as $k => $v) {
            if (!empty($LeadAttr[$v]["attr_type"])) {
                switch ($LeadAttr[$v]["attr_type"]) {
                    case 4://短字符型 判断是否为引用
                        if (!empty($LeadAttr[$v]["referred_by"])) {
                            $res_data[$k] = $LeadAttr[$LeadAttr[$v]["referred_by"]]["tbl_name"] . "." . $LeadAttr[$LeadAttr[$v]["referred_by"]]["fld_name"]. " " . $LeadAttr[$LeadAttr[$v]["referred_by"]]["fld_name"];
                        } else {
                            $res_data[$k] = $LeadAttr[$v]["tbl_name"] . "." . $LeadAttr[$v]["fld_name"] . " " . $LeadAttr[$v]["fld_name"];
                        }
                        break;
                    case 15://日期型
                        $res_data[$k] = "to_char(" . $LeadAttr[$v]["tbl_name"] . "." . $LeadAttr[$v]["fld_name"] . ", 'yyyy-mm-dd hh24:ii:ss') as " . $LeadAttr[$v]["fld_name"];
                        break;
                    case 16://时间型
                        $res_data[$k] = "to_char(" . $LeadAttr[$v]["tbl_name"] . "." . $LeadAttr[$v]["fld_name"] . ", 'yyyy-mm-dd hh24:ii:ss') as " . $LeadAttr[$v]["fld_name"];
                        break;
                    default://其他
                        if (!empty($LeadAttr[$v]["fld_name"])) {
                            $res_data[$k] = $LeadAttr[$v]["tbl_name"] . "." . $LeadAttr[$v]["fld_name"]. " " . $LeadAttr[$v]["fld_name"];
                        }
                        break;
                }
            }
        }

        return array_unique($res_data);
    }

    /**
     * GetInfoList 查询列表页面所有数据
     * @param array $ListFormat 列表布局
     * @param array $LeadAttr 线索的所有属性信息
     * @param array $obj_data 主对象相关的资料
     * @param int $OrgID 组织ID
     * @param array $Where 判断条件
     * @return json 返回前台调用的json数组
     */
    public function GetInfoList2($ListFormat, $LeadAttr, $obj_data, $OrgID, $where = array()) {
        //要返回的JSON由 数据 当前页 总页数 数据总数 五部分组成

        foreach ($ListFormat as $k => $v) {
            $arr = explode('.', $v);

            $list_format[] = $arr[0] . '.' . $arr[1];
            if (isset($arr[2])) {
                $ref_list[$arr[0] . '.' . $arr[1]][] = $arr[2];
            }
        }

        $first_tbl = $obj_data["MAIN_TABLE"]; //主表名称
        $tbl_data = $this->GetTbl($list_format, $LeadAttr); //获取表名
        $fld_data = $this->GetFld2($list_format, $LeadAttr); //获取字段名
        $data["ColNames"] = json_decode($this->objects->GetListColNames($list_format, $LeadAttr, $obj_data));
        //array_shift($data["ColNames"]);

        foreach ($ListFormat as $k => $v) {
            $ar = explode('.', $v);

            if ($LeadAttr[$ar[0] . '.' . $ar[1]]['is_ref_obj']) {
                if (isset($ar[2])) {
                    $this_obj = $LeadAttr[$ar[0] . '.' . $ar[1]]['ref_obj_name'];
                    $rrr = $this->attr->object_attr($OrgID, $this_obj);
                    $data["ColNames"][$k] = $data["ColNames"][$k] . ':' . $rrr[$this_obj . '.' . $ar[2]]['label'];
                }
            }
        }

        array_push($fld_data, $first_tbl . "." . ucfirst($obj_data["KEY_ATTR_FLD"]));
        $count_tbl = count($tbl_data); //获取得到的表数目
        //判断查询是否有过滤条件
        if (!empty($where)) {
            $this->db->where_in($first_tbl . "." . $obj_data['KEY_ATTR_FLD'], $where);
        }

        $this->db->select($fld_data);
        //如果只有一张表无需使用join
        if ($count_tbl == 1) {
            $this->db->from(current($tbl_data));
        } else {
            $this->db->from($first_tbl);
            foreach ($tbl_data as $k => $v) {
                $join_on = "$first_tbl." . $obj_data["KEY_ATTR_FLD"] . " = $v." . $obj_data["KEY_ATTR_FLD"] . " and $v.ORG_ID = $OrgID";
                if ($v == $first_tbl) {
                    continue;
                }
                $this->db->join($v, $join_on, "inner");
            }
        }
        /**
         * 拼接过滤条件 判断 表中主键是否大于0 是否为已删除数据  按创建时间 排序
         */
        $FilterPriKey = $first_tbl . "." . $obj_data["KEY_ATTR_FLD"] . ">=";
        //$obj_data['IS_RECYCLABLE'] 是否放入回收站  如果是0则直接删除 如果是1则逻辑删除所以需在查询时做逻辑判断
        if ($obj_data['IS_RECYCLABLE']) {
            $IsDEleteKey = $first_tbl . "." . "is_deleted";
            $FilterWhere[$IsDEleteKey] = 0;
        }
        $FilterWhere[$FilterPriKey] = 1;
        $OrgIDKey = $first_tbl . "." . "org_id";
        $FilterWhere[$OrgIDKey] = 1;
        $this->db->where($FilterWhere);
        $this->db->order_by($first_tbl . "." . $obj_data["KEY_ATTR_FLD"], "desc");
        /**
         * 拼接过滤条件结束
         */
        //开始分页

        $data['list_data'] = $this->db->get()->result_array();
        //将ID主键压入返回的数组字段中

        array_push($list_format, $obj_data["KEY_ATTR_NAME"]);

        if (!empty($data)) {
            foreach ($data['list_data'] as $k => $v) {
                foreach ($list_format as $kk => $vv) {
                    if ($LeadAttr[$vv]['is_ref_obj']) {
                        $ff = $LeadAttr[$vv]['referred_by'];         
                        $list[$k][$vv] = $v[strtoupper($LeadAttr[$ff]['fld_name'])];
                    } else {
                        $list[$k][$vv] = $v[strtoupper($LeadAttr[$vv]['fld_name'])];
                    }
                }
            }

            // $list_data = $this->FldConverName($list, $list_format);
            // $data['list_data'] = $this->FldConverName($data['list_data'], $list_format);

            /*             * *开始解析数据类型*** */
            $data['list_data'] = $this->GetDataType($list, $LeadAttr, $OrgID);

            foreach ($data['list_data'] as $k => $v) {
                foreach ($list_format as $kk => $vv) {
                    $data['need_data1'][$k][$vv] = $v[$vv];
                }
            }

            //找引用
            foreach ($data['need_data1'] as $k => $v) {
                foreach ($ListFormat as $kk => $vv) {
                    $tt = explode('.', $vv);
                    $data['need_data'][$k][$vv] = $v[$tt[0] . '.' . $tt[1]];

                    if (isset($tt[2])) {
                        foreach ($ref_list[$tt[0] . '.' . $tt[1]] as $kkk => $vvv) {

                            $data['need_data'][$k][$vv] = $list[$k][$tt[0] . '.' . $tt[1]];
                        }
                    }
                }
            }


            foreach ($data['need_data'] as $k => $v) {
                foreach ($v as $kk => $vv) {                    
                    $aa = explode('.', $kk);//分割字符串 eg:Lead.Campaign.owner
                    if(isset($aa[2])){
                        
                        $obj=$this->obj->NameGetObj($OrgID, $LeadAttr[$aa[0].'.'.$aa[1]]['ref_obj_name']);

                        $AttrBean['where']=array(
                            'org_id'=>$OrgID,
                            'ATTR_NAME'=>$obj['OBJ_NAME'].'.'.$aa[2]);
                        $AttrBean['select']='FLD_NAME,TBL_NAME,IS_REF_OBJ,REF_OBJ_NAME,REFERRED_BY';
                        $ddd=$this->attr->SelectOne($AttrBean);//找到需要的字段名
                        if($ddd['IS_REF_OBJ']){
                            $Bean['where']=array(
                                'org_id'=>$OrgID,
                                'ATTR_NAME'=>$ddd['REFERRED_BY']);
                            $Bean['select']='FLD_NAME,TBL_NAME';
                            $ref=$this->attr->SelectOne($Bean);
                            $where1=array(
                                $obj['KEY_ATTR_FLD'] => $vv,
                                "org_id" => $OrgID,

                                );
                            $rer = $this->db->select($ref['FLD_NAME'].' as val')->from($ref['TBL_NAME'])->where($where1)->get()->result_array();
                            $refobj=$this->obj->NameGetObj($OrgID, $ddd['REF_OBJ_NAME']);
                            
                            $Bean1['where']=array(
                                'org_id'=>$OrgID,
                                'ATTR_NAME'=>$refobj['NAME_ATTR_NAME']);
                            $Bean1['select']='FLD_NAME,TBL_NAME';
                            $ref1=$this->attr->SelectOne($Bean1);
                            
                            $where2=array(
                                $refobj['KEY_ATTR_FLD'] => $rer[0]['VAL'],
                                "org_id" => $OrgID,

                                );
                            $rer2 = $this->db->select($ref1['FLD_NAME'].' as val')->from($ref1['TBL_NAME'])->where($where2)->get()->result_array();
                                    
                            $data['need_data'][$k][$kk]=$rer2[0]['VAL'];
                        }else{
                            $where3=array(
                                $obj['KEY_ATTR_FLD'] => $vv,
                                "org_id" => $OrgID,

                                );
                            $rer3 = $this->db->select($ddd['FLD_NAME'].' as val')->from($ddd['TBL_NAME'])->where($where3)->get()->result_array();
                            $data['need_data'][$k][$kk]=$rer3[0]['VAL'];
                        }      
                    }
                }
            }
            
        }

        /*         * *完成解析数据类型*** */
        //总页数
        return $data;
    }

    /**
     * FindRefAll 列表属性引用相关字段
     *
     *
     */
    public function FindRefAll($object_attr) {
        $org_id = $this->session->userdata('org_id');
        foreach ($object_attr as $k => $v) {
            if ($v['is_disp_list'] && !$v['is_hidden']) {
                if ($v['is_ref_obj']) {
                    $obj_attr = $this->attr->object_attr($org_id, $v['ref_obj_name']);
                    foreach ($obj_attr as $kk => $vv) {

                        if ($vv['is_disp_list'] && !$vv['is_hidden'] && $vv['is_ref_obj']) {
                            //$scr_data[$k]['label']=$v['label'];
                            $arr = explode(".", $kk);
                            $scr_data[$kk]['attr_name'] = $k . '.' . $arr[1];
                            $scr_data[$kk]['label'] = $v['label'] . ':' . $vv['label'];
                        }
                    }
                }
            }
        }

        return $scr_data;
    }

    /**
     * getObjData   得到对象的属性和属性的中文名称和一些需要显示的名称
     * @param $obj    对象名称
     * return array $data_arr  对象属性数组
     */
    public function getObjData($obj) {
        $select = array(
            'attr.ATTR_NAME',
            'str.LABEL',
            'str.LABEL',
            'attr.is_ref_obj',
            'attr.referred_by',
            'attr.attr_type',
            'attr.ref_obj_name',
            'attr.is_primary',
            'attr.is_must'
        );
        $condition = array(
            'str.lang_id ' => '2',
            'attr.org_id' => '1',
            'str.org_id ' => '1',
            'attr.is_hidden ' => '0',
            'attr.obj_name' => $obj,
            'attr.is_disp_other' => "1",
        );
        $attr_type = array('18', '19', '20');
        $this->db->where_not_in('attr_type', $attr_type);
        $this->db->from('DD_ATTRIBUTE attr')->join('dd_dict_str str', 'attr.attr_name = str.dict_name', 'left');
        $query = $this->db->select($select)->where($condition)->get()->result_array();
        foreach ($query as $key => $value) {
            if ($value['IS_REF_OBJ'] == 0) {
                if ($value["ATTR_TYPE"] == 12 || $value["ATTR_TYPE"] == 13) {
                    $value['ATTR_NAME'] = $value['ATTR_NAME'] . ".value";
                    $data['system'][] = $value;
                } else {
                    $data['system'][] = $value;
                }
            } else {
                $new_value = $this->getObjDataNoRef($value['ATTR_NAME'], $value['REF_OBJ_NAME']);
                $new_value["IS_MUST"] = $value["IS_MUST"];
                $data['ref'][$value['LABEL']] = $new_value;
            }
        }

        if (count($data)) {
            return $data;
        } else {
            return array();
        }
    }

    /**
     * getObjData   得到对象的属性和属性的中文名称和一些需要显示的名称和上面的区别是只是查询引用字段
     * @param $obj    对象名称
     * return array $data_arr  对象属性数组
     */
    public function getObjDataNoRef($attr_name, $obj) {
        $select = array(
            'attr.ATTR_NAME',
            'str.LABEL',
            'str.LABEL',
            'attr.is_ref_obj',
            'attr.referred_by',
            'attr.attr_type',
            'attr.ref_obj_name',
        );
        $condition = array(
            'str.lang_id ' => '2',
            'attr.org_id' => '1',
            'str.org_id ' => '1',
            'attr.obj_name' => $obj,
            'attr.IS_REF_OBJ' => '0'
        );
        $attr_type = array('18', '19', '20');
        $this->db->where_not_in('attr_type', $attr_type);
        $this->db->from('DD_ATTRIBUTE attr')->join('dd_dict_str str', 'attr.attr_name = str.dict_name', 'left');
        $query = $this->db->select($select)->where($condition)->get()->result_array();
        if (count($query)) {
            foreach ($query as $k => $val) {
                $query[$k] = str_replace($obj, $attr_name, $val);
            }
            return $query;
        } else {
            return array();
        }
    }

    /**
     * deleteRepeat 去除二维数组中每个字段相同的值
     * @param  二维数组
     * @return 去重后的二维数组
     */
    public function deleteRepeat($arr, $checkbox) {
        if (!( is_array($arr) && count($arr) != 0 )) {
            return false;
        }
        foreach ($arr as $key => $value) {
            foreach ($value as $k => $v) {
                //添加不需要过滤不要删除的重复的字段
                if (in_array($k, $checkbox)) {
                    $i = 0;
                    foreach ($arr as $kk => $vv) {
                        if (isset($vv[$k])) {
                            if ($vv[$k] == $v) {
                                if ($i == 0) {
                                    $i++;
                                } else {
                                    unset($arr[$kk]);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $arr;
    }

    /**
     * 	batch_up_attr	公共	查询出来的可以批量修改的属性
     * 	@return	array	 查询出来的可以批量修改的属性数组
     */
    public function batch_up_attr($obj_name) {
        $select = array(
            'a.attr_name',
            'b.label'
        );
        $condition = array(
            'a.is_mass_update' => '1',
            'a.org_id' => '1',
            'b.org_id' => '1',
            'a.obj_name' => $obj_name,
            'b.lang_id' => '2',
        );
        $query = $this->db->from("dd_attribute a")->
                        join('dd_dict_str b', 'a.attr_name = b.dict_name', 'left')->
                        where($condition)->
                        select($select)->get()->result_array();
        if (count($query)) {
            return $query;
        } else {
            return array();
        }
    }

    /**
     * 有待完善
     */
    public function pub_button($OrgID, $data) {
        $random = rand(1,10000);
        $type_arr = $this->type->ObjGetType($OrgID, $data["ObjType"]);

        $but_array['1'] = array(
            "name" => "新建",
            "icon" => "fa fa-pencil",
            "colour" => "btn btn-primary",
        );
        //新增判断类型
        if ($type_arr) {
            foreach ($type_arr as $k => $v) {
                $but_array['1']['dropdown'][] = array(
                    "name" => $v["Name"],
                    "url" => base_url() . "index.php/www/objects/add?obj_type=" . $data["ObjType"] . "&type_id=" . $v["ID"],
                );
            }
        } else {
            $but_array['1']['url'] = base_url() . "index.php/www/objects/add?obj_type=" . $data["ObjType"];
        }


        $but_array['2'] = array(
            "name" => "高级查询",
            "icon" => "fa fa-pencil",
            "colour" => "btn btn-primary",
            "id" => "lead_sale_seniorquery".$random,
            "javascript" => '$(document).ready(function () {
                             var seniorquery_attr = ' . $data['SeniorQueryAttrJson'] . ';
                             $("#lead_sale_seniorquery'.$random.'").SeniorQuery({
                             SelectAttr: seniorquery_attr,
                             ListUrl: "' . base_url() . 'index.php/www/objects/list_json?obj_type=' . $data["ObjType"] . '",
                             ListDiv: "#'.$data["div_name"].'" }); });'
        );

        $but_array['3'] = array(
            "name" => "批量导入",
            "icon" => "fa fa-pencil",
            "colour" => "btn btn-info",
            "id" => "import",
            "javascript" => ' $(document).ready(function () {

        $("#import").AjaxDialog({
            DialogUrl: "' . base_url() . 'index.php/www/objects/import?obj_type=' . $data["ObjType"] . '",
            DialogTitle: "批量导入",
            DialogWidth: 700,
            DialogHeight: 400
        });});'
        );
        $but_array['4'] = array(
            "name" => "批量导出",
            "icon" => "fa fa-pencil",
            "colour" => "btn btn-primary",
            "id" => "export",
            "javascript" => '$("#export").click(function () {
                        var data = "";
                        $DialogDivID = "DialogDiv" + $(this).attr("id");
                        
                        data = $("#'. $data['obj_name'] . '_page-content").LeeTable(\'GetSelectedID\');
                        console.debug(data);
                        if ($(\'#\' + $DialogDivID).length == 0) {
                            $(this).after(\'<div id="\' + $DialogDivID + \'" style="display:none;"></div>\');
                        }
                        $.ajax({
                            \'type\': \'post\',
                            \'data\': {"data": data},
                            \'success\': function (data) {
                                $(\'#\' + $DialogDivID).html(data);
                            },
                            \'url\': "' . base_url() . 'index.php/www/objects/export?obj_type=' . $data['ObjType'] . '",
                            \'cache\': false
                        });
                        $("#" + $DialogDivID).dialog({
                            title: "批量导出",
                            modal: true,
                            width: 700,
                            height: 400,
                            
                        });
                        $(\'#\' + $DialogDivID).dialog(\'open\');
                        

                    });'
        );
        $but_array['5'] = array(
            "name" => "批量更新",
            "icon" => "fa fa-pencil",
            "colour" => "btn btn-warning",
            "id" => "batch_up",
            "javascript" => '$(document).ready(function () {  $("#batch_up").AjaxDialog({
            DialogUrl: "' . base_url() . 'index.php/www/objects/batchUp?div_id='.$data["div_name"].'&obj_type=' . $data["ObjType"] . '",
            DialogTitle: "批量更新",
            DialogWidth: 500,
            DialogHeight: 400});

            });'
        );

        // $but_array['6'] = array(
        //     "name" => "查重",
        //     "icon" => "fa fa-pencil",
        //     "colour" => "btn btn-pink",
        // );

        $but_array["7"] = array(
            "name" => "批量分配员工",
            "icon" => "fa fa-pencil",
            "colour" => "btn btn-pink",
        );
        $but_array['7']['dropdown'][] = array(
            "name" => "批量分配相关员工",
            "id" => "assign_staff ",
            "javascript" => '$(document).ready(function () {  $("#assign_staff").AjaxDialog({
                  DialogUrl: "' . base_url() . 'index.php/www/objects/assign_staff?div_id='.$data["div_name"].'&button=assign_staff&employees=0&obj_type=' . $data["ObjType"] . '",
                  DialogTitle: "批量分配相关员工",
                  DialogWidth: 400,
                  DialogHeight: 200});});'
        );

        $but_array['7']['dropdown'][] = array(
            "name" => "批量分配负责员工",
            "id" => "assign_staff2",
            "javascript" => '$(document).ready(function () {  $("#assign_staff2").AjaxDialog({
                  DialogUrl: "' . base_url() . 'index.php/www/objects/assign_staff?div_id='.$data["div_name"].'&button=assign_staff2&employees=1&obj_type=' . $data["ObjType"] . '",
                  DialogTitle: "批量分配负责员工",
                  DialogWidth: 400,
                  DialogHeight: 200});});'
        );
        $but_array['7']['dropdown'][] = array(
            "name" => "批量回收员工",
            "id" => "recyc_staff",
            "javascript" => '$(document).ready(function () {  $("#recyc_staff").AjaxDialog({
                  DialogUrl: "' . base_url() . 'index.php/www/objects/recyc_staff?div_id='.$data["div_name"].'&obj_type=' . $data["ObjType"] . '",
                  DialogTitle: "批量回收员工",
                  DialogWidth: 400,
                  DialogHeight: 200});});'
        );


        $data["but_array"] = $but_array;
        return $data;
    }

    /**
     * BLOB字段更新方法
     * demo:
     * $table = "tc_opportunity";
     *   $set["description"] = "中华人民共和国123";
     *  $where["optnty_id"] = "1196003";
     * $this->objects->_blob_update($table, $where, $set);
     * die;
     * @param string $table 要更新的表名
     * @param array $where 要更新的条件
     * @param array $set   要更新的字段及要更新的值
     * @return bool 返回执行是否成功
     */
    public function _blob_update($table, $where, $set) {
        $conn = $this->db->conn_id; //获取ORACLE连接数据源
        $set_fields = $this->_set($set); //分拆传过来的字段及要更新的VALUE值
        $set_fields_bink_name = ":" . $set_fields['blob_key']; //得到预处理的字段，预处理命名以字段名称为主
        $set_fields_prefix = $set_fields['blob_key'] . " = EMPTY_BLOB()"; //拼接UPDATE语句 SET 里的内容，因BLOB字段 只处理,所以刚开始至空
        $set_fields_returning = $set_fields['blob_key'] . " INTO " . $set_fields_bink_name; //利用ORACLE的returning特性对BLOB字段进行更新
        $msg = base64_encode($set_fields['blob_value']); //对传递过来的数据进行BASE加密
        //下面开始拼接SQL语句
        $sql = " update $table set ";
        $sql .= " " . $set_fields_prefix . " ";
        $sql .= " where " . $this->_where($where);
        $sql .= " RETURNING " . $set_fields_returning . " ";
        //定义解析器
        $clob = oci_new_descriptor($conn, OCI_D_LOB);
        //执行预处理语句
        $stid = oci_parse($conn, $sql);
        //绑定字段
        oci_bind_by_name($stid, $set_fields_bink_name, $clob, -1, OCI_B_BLOB);
        //提交数据
        if (version_compare(PHP_VERSION, '5.3.1') >= 0) {
            oci_execute($stid, OCI_NO_AUTO_COMMIT); // use OCI_DEFAULT for PHP >= 5.3.1
        } else {
            oci_execute($stid, OCI_DEFAULT); // use OCI_DEFAULT for PHP <= 5.3.1
        }
        if (!$clob->save($msg)) {
            oci_rollback($conn);
            return false;
        }
        oci_commit($conn);
        //释放内存
        oci_free_statement($stid);
        //oci_close($conn);
        return true;
        /*
          $mykey = 1196003;  // arbitrary key for this example;
          //$str = base64_encode("中华人民共和国ddd");
          $str = base64_encode("中华人民共和国ddd");
          $clob = oci_new_descriptor($conn, OCI_D_LOB);
          $sql = "UPDATE
          tc_opportunity
          SET
          description = EMPTY_BLOB()
          WHERE
          optnty_id = :optnty_id
          RETURNING
          description INTO :description";

          $stid = oci_parse($conn, $sql);
          oci_bind_by_name($stid, ":optnty_id", $mykey);
          oci_bind_by_name($stid, ":description", $clob, -1, OCI_B_BLOB);
          oci_execute($stid, OCI_DEFAULT); // use OCI_DEFAULT for PHP <= 5.3.1
          $clob->save($str);
          oci_commit($conn);
          oci_free_statement($stid);
          oci_close($conn);
         */
        die;
    }

    /**
     * 拼出WHERE条件
     */
    protected function _where($where) {
        $sql = "";
        foreach ($where as $key => $value) {
            $sql .= "$key = $value and ";
        }
        $sql .= " 1 = 1 ";
        return $sql;
    }

    /**
     * 拼出SET条件
     */
    protected function _set($set) {
        $sql = array();
        foreach ($set as $key => $value) {
            $sql["blob_key"] = $key;
            $sql["blob_value"] = $value;
        }
        return $sql;
    }

    /**
     * 分配员工
     * @param $table_name   表名
     * @param $table_field   字段1名称
     * @param $org_id      单位id
     * @param $id         字段1值
     * @param $user_id   员工id
     * @param $is_self   员工属性
     * @param int $server   默认为0
     * @return 插入结果
     */
    public function into_user($table_name, $table_field, $org_id, $id, $user_id, $is_self, $server = 0) {
        $array_sel = array($table_field => $id, 'USER_ID' => $user_id);
        $query = $this->db->where($array_sel)->get($table_name)->result_array();
        if (count($query) == 0) {
            $array_in = array("ORG_ID" => $org_id, $table_field => $id, 'USER_ID' => $user_id, 'IS_SELF' => $is_self, "SERVER_FLAG" => $server);
            $this->db->set($array_in);
            return $this->db->insert($table_name);
        } else {
            return true;
        }
    }

    /**
     *  * 回收员工
     * @param $table_name   表名
     * @param $table_field   字段1名称
     * @param $org_id      单位id
     * @param $id         字段1值
     * @param $user_id   员工id
     * @return 插入结果
     */
    public function del_user($table_name, $table_field, $org_id, $id, $user_id) {
        $array = array("ORG_ID" => $org_id, $table_field => $id, 'USER_ID' => $user_id);
        return $this->db->delete($table_name, $array);
    }

    /**
     * @param $id      要停用启用的限制id
     * @param $obj_type  对象的类型
     * @param $data       修改的数据
     * @return mixed   修改的结果 bool
     */
    public function _disable_enable($id, $obj_type, $data) {
        $obj = $this->obj->getOneData($obj_type);
        $table_name = $obj["MAIN_TABLE"];
        $key_attr_name = $obj["KEY_ATTR_FLD"];
        $this->db->where($key_attr_name, $id);
        return $this->db->update($table_name, $data);
    }

    /**
     * view_pub_button view界面的公共按钮和私有按钮
     * @param $OrgID  企业编号
     * @param $data   前台数据
     * @return mixed
     */
    public function view_pub_button($OrgID, $data) {

        $type_arr = $this->type->ObjGetType($OrgID, $data["obj_type"]);
        $but_array['1'] = array(
            "name" => "新建",
            "icon" => "fa fa-file",
            "colour" => "btn",
        );
        //新增判断类型
        if ($type_arr) {
            foreach ($type_arr as $k => $v) {
                $but_array['1']['dropdown'][] = array(
                    "name" => $v["Name"],
                    "url" => base_url() . "index.php/www/objects/add?obj_type=" . $data["ObjType"] . "&type_id=" . $v["ID"],
                );
            }
        } else {
            $but_array['1']['url'] = base_url() . "index.php/www/objects/add?obj_type=" . $data["ObjType"];
        }
        $but_array['2'] = array(
            "name" => "列表查询",
            "icon" => "fa fa-list",
            "colour" => "btn btn-info",
            "url" => base_url() . "index.php/www/objects/lists?obj_type=" . $data["obj_type"]
        );
        $but_array['3'] = array(
            "name" => "编辑",
            "icon" => "fa fa-edit",
            "colour" => "btn btn-success",
            "url" => base_url() . "index.php/www/objects/edit?obj_type=" . $data["obj_type"] . "&id=" . $data["id"]
        );
        $but_array['4'] = array(
            "name" => "删除",
            "icon" => "fa fa-trash-o",
            "colour" => "btn btn-warning",
            "id" => "del",
            "javascript" => '   $(function(){
                  $("#del").click(function(){
           if (confirm("确定要删除数据吗？")) {
               var del_url = "' . site_url() . '/www/objects/del";
               $.ajax({
                   type: "GET",
                   url: del_url,
                   data: {id: ' . $data["id"] . ' ,obj_type: ' . $data["obj_type"] . '},
                   success: function (data) {
                       if (data == "success") {
                           alert("删除数据成功");
                           history.go(-1);
                       } else {
                           alert("网络异常，删除失败！");
                       }
                   }
               });
           }
       })

   })'
        );
//        $but_array['5'] = array(
//            "name" => "修改类型",
//            "icon" => "fa fa-tag",
//            "colour" => "btn btn-danger",
//        );
        $but_array['6'] = array(
            "name" => "停用",
            "icon" => "fa fa-check",
            "colour" => "btn btn-inverse",
            "id" => "disable",
            "javascript" => ' $(function(){
                    $("#disable").click(function(){
                           if (confirm("确定要停用数据吗？")) {
                            var disable_url = "' . site_url() . '/www/objects/disable_enable";
                            $.ajax({
                                type: "GET",
                                url: disable_url,
                                data: {id: ' . $data["id"] . ' ,obj_type: ' . $data["obj_type"] . ', stop_flag:1},
                                success: function (data) {
                                    if (data == "success") {
                                        alert("停用数据成功");
                                        window.history.go(0);
                                    } else {
                                        alert("网络异常，停用失败！");
                                    }
                                }
                            });
                        }
                    })
                  })'
        );
        $but_array['7'] = array(
            "name" => "启用",
            "icon" => "fa fa-times",
            "colour" => "btn btn-inverse",
            "id" => "enable",
            "javascript" => ' $(function(){
                    $("#enable").click(function(){
                           if (confirm("确定要启用数据吗？")) {
                            var disable_url = "' . site_url() . '/www/objects/disable_enable";
                            $.ajax({
                                type: "GET",
                                url: disable_url,
                                data: {id: ' . $data["id"] . ' ,obj_type: ' . $data["obj_type"] . ', stop_flag:0},
                                success: function (data) {
                                    if (data == "success") {
                                        alert("启用数据成功");
                                        window.history.go(0);
                                    } else {
                                        alert("网络异常，启用失败！");
                                    }
                                }
                            });
                        }
                    })
                  })'
        );
        $private_but['Lead']['1'] = array(
            "name" => "转化",
            "icon" => "fa fa-refresh",
            "colour" => "btn btn-pink",
            "id" => "clues_into",
            "javascript" => ' $(document).ready(function() {
                    $("#clues_into").AjaxDialog({
                        DialogUrl: "' . site_url() . '/www/lead/leadConversion",
                        DialogTitle:"线索转化",
                        DialogWidth: 560,
                        DialogHeight: 500,
                        DialogData:{id: ' . $data["id"] . ' ,obj_type: ' . $data["obj_type"] . '},
                        DialogButtons: [{
                            text:"确定",
                            Class:"btn btn-primary",
                            click: function(){
                                var sub = true;
                                if($("#input_key_Account_Owner").val().length == 0){
                                    sub = false;
                                    alert("请选择客户所有者");
                                    return;
                                }
                                if($("#contact").prop("checked") && $("#input_key_Contact_Owner").val().length == 0){
                                    sub = false;
                                    alert("请选择联系人所有者");
                                    return;
                                }
                                if($("#opportunity").prop("checked") && $("#input_key_Opportunity_Owner").val().length == 0){
                                    sub = false;
                                    alert("请选择联系人所有者");
                                    return;
                                }
                                if(sub){
                                    $(this).dialog("close");
                                    $("#cluesIntoData").submit();
                                    alert("成功")
                                }
                            }

                        }]
                    }) })'
        );

        $private_but['Opportunity']['1'] = array(
            "name" => "阶段升迁",
            "icon" => "fa fa-refresh",
            "colour" => "btn btn-pink",
            "id" => "phase_promotion ",
            "javascript" => ' $(document).ready(function() {
                $("#phase_promotion").AjaxDialog({
                DialogUrl: "' . base_url() . 'index.php/www/opportunity/phase_promotion?id='.$data['id'].'",
                DialogTitle: "阶段升迁",
                DialogWidth: 300,
                DialogHeight: 200
              });
            })'
        );

        $data["but_array"] = $but_array;
        $data["private_but"] = $private_but;
        return $data;
    }

    /**
     * get_index_attr   得到全文索引需要查询的属性
     * @param $obj_name 对象名称
     * @return array   返回查询字段的结果集
     */
    public function get_index_attr($obj_name, $org_id) {
        $select = array(
            'is_search_field',
            'attr_name',
        );
        $condition = array(
            'obj_name' => $obj_name,
            'is_search_field !=' => '0',
            'org_id' => $org_id
        );
        $query = $this->db->select($select)->where($condition)->get("dd_attribute")->result_array();
        $where = "";
        foreach ($query as $k => $v) {
            $res[$v['IS_SEARCH_FIELD']] = $v['ATTR_NAME'];
        }
        if (count($query)) {
            return $res;
        } else {
            return array();
        }
    }

    /**
     * get_index_fld   得到全文索引需要查询的字段
     * @param $where_arr   属性数组
     * @return array   返回查询字段的结果集
     */
    public function get_index_fld($where_value, $arr) {
        //$for_data = $this->parse_formart($where_arr); //取出引用的字段
        $org_id = $this->session->userdata('org_id');
        $i = 0;
        $rel = "";
        foreach ($arr as $key => $value) {
            $where_arr[++$i] = array(
                "attr" => $value,
                "action" => "LIKE",
                "value" => $where_value
            );
            $rel .= $i . " or ";
        }
        $rel = substr(trim($rel), 0, -2);
        $where = array(
            "where" => $where_arr,
            "rel" => $rel
        );
        return $where;
    }

    /**
     * attr_name  获取对象属性的所有语言
     * @param $attr  对象名称
     * @return mixed 所有语言的数组
     */
    public function attr_name($attr) {
        $select = array(
            'dict_name',
            'label',
        );

        $condition = array(
            'org_id' => $this->session->userdata('org_id'),
            'dict_name' => $attr
        );
        $query = $this->db->select($select)->where($condition)->get("dd_dict_str")->result_array();
        return $query;
    }

    /**
     * jqGridModel 全文索引前台的jqGrid数据模型
     * @param $arr  要显示的属性
     * @return mixed  数据模型
     */
    public function jqGridModel($arr) {
        foreach ($arr as $key => $value) {
            $lan = $this->attr_name($value);
            $data['col'][] = array("Name" => $value,"LangEn" => $lan[1]["LABEL"] );
        }

        return $data;
    }

    /**
     * detail 查询对象的类型有没有明细
     * $obj_type 对象类型
     * return 子对象名称
     */
    public function detail($obj_type) {
        $select = array(
            'dtl_obj_name ',
        );
        $condition = array(
            'org_id' => $this->session->userdata('org_id'),
            'obj_type' => $obj_type
        );
        $query = $this->db->select($select)->where($condition)->get("dd_object")->result_array();
        if (empty($query[0]["DTL_OBJ_NAME"])) {
            return false;
        } else {
            return $query[0]["DTL_OBJ_NAME"];
        }
    }

    
    /**
     * @param $itme    显示的属性
     * @return $arr   显示的数据的中文名称
     */
    public function detail_title($itme) {
        foreach ($itme as $value) {
            if (!count($this->attr_name($value))) {
                continue;
            }
            $title = $this->attr_name($value);
            $arr[$value] = $title[1]["LABEL"];
        }
        return $arr;
    }

    /**
     * calculate   得到名称运算的对象和符号
     * @param $obj_name    对象名称
     * @return $data   运算法则的数组
     */
    public function calculate($obj_name,$is_point=1) {
        $select = array(
            'formula_expr ',
            'attr_name'
        );
        $condition = array(
            'org_id' => $this->session->userdata('org_id'),
            'obj_name' => $obj_name
        );
        $query = $this->db->where('formula_expr is not null ',null,false) -> select($select)->where($condition)->get("dd_attribute")->result_array();
        if(strpos($query[0]['FORMULA_EXPR'],"+")){
            $symbol = "+";
        }else  if(strpos($query[0]['FORMULA_EXPR'],"-")){
            $symbol = "-";
        }else  if(strpos($query[0]['FORMULA_EXPR'],"*")){
            $symbol = "*";
        }else  if(strpos($query[0]['FORMULA_EXPR'],"/")){
            $symbol = "/";
        }
        $data['symbol'] = $symbol;
        $data_arr = explode($symbol,$query[0]['FORMULA_EXPR']);
        if($is_point == 1){
            $data['results'] = $query[0]['ATTR_NAME'];
            $data["first"] = $obj_name.".".$data_arr[0];
            $data["last"] = $obj_name.".".$data_arr[1];
        }else{
            $data['results'] = str_replace(".",'_',$query[0]['ATTR_NAME']) ;
            $data["first"] = $obj_name."_".$data_arr[0];
            $data["last"] = $obj_name."_".$data_arr[1];
        }
        return $data;
    }

    /**
     * @param $all_attr    所有属性
     * @param $detail_title  前台显示属性
     * @param $attr 所有属性
     * @return $mydata  明细所有属性
     */
    public function detail_col($all_attr,$detail_title,$attr){
        foreach ($detail_title as $key => $value) {
            foreach ($all_attr as $k => $v) {
               if ($key == $v["name"]) {
                    $mydata[$key]["AttrType"] = $v["attr_type"];
                    $mydata[$key]["Name"] = $v["name"];
                    $mydata[$key]["LangEn"] = $v["label"];
                    if(isset($v["form_data"])){
                        $mydata[$key]["FormData"] = $v["form_data"];
                    }
                    $mydata[$key]["FormData"]["Name"] = $v["name"];
                    if($attr[$key]["is_must"] == 1){
                        $mydata[$key]["IsMust"] = true;
                    }else{
                        $mydata[$key]["IsMust"] = false;
                    }

                }
            }
        }
      return $mydata;
    }

    public function detail_is_there($where,$table_name){
          $count = $this->db->where($where) ->count_all_results($table_name);
          return  $count;

    }

    /**
     * @param $data 插入明细的数据
     * @param $module  插入对象
     * @param int $OrgID
     * @param $UserID
     * @return mixed 插入成功或者失败信息
     */

    public function detail_add($data, $module, $OrgID = 1, $UserID) {
        $this->load->model('admin/nextid', 'nextid');


        $time = date("Y-m-d H:i:s");

        $type_id = $data[$module . 'Type'];
        $object_attr = $this->attr->object_attr($OrgID, $module); //查出对象对应属性信息
        $tbl_name = $this->GetAllTbl($OrgID, $module);
        foreach ($tbl_name as $k => $v) {
            $insert[$v] = array();
        }

        $obj_data = $this->obj->NameGetObj($OrgID, $module);
        $main_tbl = $obj_data['MAIN_TABLE'];
        $pk = $obj_data['KEY_ATTR_FLD'];
        $IDformat = $this->nextid->NewFormatID($type_id, $obj_data, $OrgID);
        if (!empty($IDformat['FORMAT'])) {
            $FormatID = $this->nextid->NewID('LEAD_USERID_' . $type_id . '_' . date('Ymd'), $OrgID);

            $strarr = array(
                '{YYYY}' => date('Y'),
                '{MM}' => date('m'),
                '{DD}' => date('d'),
                '{0000}' => str_pad($FormatID, 4, "0", STR_PAD_LEFT),
            );

            $lead_userid = strtr($IDformat['FORMAT'], $strarr);
            $data[$IDformat['ATTR_NAME']] = $lead_userid;
        }

        $nextid = $this->nextid->NewID($main_tbl, $OrgID);
        $userdata = $this->user->SelectOne($OrgID, $UserID);
        // foreach ($ndata as $k => $v) {
        //     if ($object_attr[$k]['is_must'] && empty($data[$k])) {
        //         $res['res'] = "fail";
        //         $res['error_code'] = "201";
        //         $res['msg'] = "缺少\"" . $k . "\"参数，保存失败！";
        //         echo "\"" . $object_attr[$k]['label'] . "\"未填，保存失败！";
        //         return $res;
        //     }
        // }

        $ndata = $this->transform($data, $module, $OrgID);

        if ($ndata == false || empty($ndata)) {
            $res['res'] = 'fail';
            $res['msg'] = '保存失败！';
            return $res;
        }
        foreach ($ndata as $k => $v) {
            if (empty($v)) {
                continue;
            }
            $insert[$object_attr[$k]['tbl_name']][$k] = $v;
        }

        //$this->db->trans_start();

        foreach ($insert as $k => $v) {
            if (!empty($v)) {
                foreach ($v as $kk => $vv) {

                    switch ($object_attr[$kk]['attr_type']) {
                        case 4:
                            if($object_attr[$kk]['is_ref_obj']){
                                $this->db->set($object_attr[$object_attr[$kk]['referred_by']]['fld_name'], $vv);
                            }else{
                                $this->db->set($object_attr[$kk]['fld_name'], $vv);
                            }
                            break;
                        case 6://blob单独处理
                            break;
                        case 1:
                        case 2:
                        case 3:
                        case 11:
                        case 12:
                        case 13:
                        case 18:
                        case 19:
                        case 20:
                            // if(!is_numeric($vv)){
                            //     $res['res'] = "fail";
                            //     $res['error_code'] = "204";
                            //     $res['msg'] = "\"".$kk."\"必须是数值!";
                            //     return $res;
                            // }
                            $this->db->set($object_attr[$kk]['fld_name'], $vv, false);
                            break;
                        case 15:
                            if (!empty($vv)) {
                                //$vv = dataReduce8('Y-m-d', $vv);
                                $this->db->set($object_attr[$kk]['fld_name'], "to_date('" . $vv . "','yyyy-mm-dd hh24:mi:ss')", false);
                            }

                            break;
                        case 16:
                            if (!empty($vv)) {
                                $vv = dataReduce8('Y-m-d H:i:s', $vv);
                                $this->db->set($object_attr[$kk]['fld_name'], "to_date('" . $vv . "','yyyy-mm-dd hh24:mi:ss')", false);
                            }

                            break;
                        default:
                            $this->db->set($object_attr[$kk]['fld_name'], $vv);
                            break;
                    }
                }
            }

            $this->db->set($pk, $nextid);

            $this->db->set('org_id', $OrgID, false);
            $this->db->insert($k);
        }
        // $this->db->trans_complete();
        // if ($this->db->trans_status() === FALSE){
        //     return false;
        // }else{
        $res['res'] = 'suc';
        $res['id'] = $nextid;
        $res['msg'] = '保存成功！';
        return $res;
        // }
    }
    
    /**
     * Objects_model::formulra_cond()
     * 解析公式数据
     * @param mixed $dtl_obj_name
     * @param mixed $org_id
     * @param mixed $coll
     * @return void
     */
    public function formulra_cond($dtl_obj_name, $lead_attr, $org_id, &$coll){
        /**
         * 1 首先先从明细对象查询回主对象拿到主对象的值
         * 2 再根据主对象查询出主对象的属性详细信息
         * 3 将子对象与主对象数组合并
         */
        $master_obj = $this->obj->getMasterObjName($dtl_obj_name, $org_id);
        $master_obj_attr = $this->attr->object_attr($org_id, $master_obj['OBJ_NAME']);
        $lead_attr = array_merge($lead_attr, $master_obj_attr);
        foreach ($coll as $k => $v) {
            if (isset($lead_attr[$k]) && $lead_attr[$k]['lee_is_calc_point'] == 1) {//判断是否为触发点
                $coll[$k]['is_calc_point'] = $lead_attr[$k]['lee_is_calc_point'];
                $calc_result_attr = explode(",", $lead_attr[$k]['lee_calc_result_attr']);
                foreach ($calc_result_attr as $calc_k => $calc_v) {
                    $coll[$k]['formulra_data'][$calc_k]['calc_result_attr'] = $calc_v;//填充值
                    $coll[$k]['formulra_data'][$calc_k]['calc_formula'] = $lead_attr[$calc_v]['lee_calc_formula'];//公式
                }
            }
        }
    }

    /**
     *根据attr名称得到对应的表和表的主键列
     * @param $attr 属性名称
     * @param $org_id 企业id
     * @return mixed
     */
    public function get_table_fld($attr,$org_id){
        $select = array(
            'tbl_name',
            'fld_name'
        );
        $condition = array(
            'org_id' => $org_id,
            'attr_name' => $attr
        );
        $query = $this->db -> select($select)->where($condition)->get("dd_attribute")->row_array();
        return $query;

    }

    /**
     * 得到主对象下所有明细的id
     * @param $arr 需要查询的表信息
     * @param $id 主对象id
     * @param $need_fld  前台需要的的字段
     * @return array id的数组
     */
    public function get_all_detail_id($arr,$id,$need_fld){
        $select = array(
            $need_fld
        );
        $condition = array(
            'org_id' => $this->session->userdata('org_id'),
             $arr["FLD_NAME"] => $id
        );
        $query = $this->db -> select($select)->where($condition)->get($arr["TBL_NAME"])->result_array();
        foreach($query as $value){
           $res[] = $value[strtoupper($need_fld)];
        }
        return $res;
    }

    /**
     * 获取所有主对象下的明细数据
     * @param $obj_data  对象信息
     * @param $KEY_ATTR_NAME 主属性
     * @param $KEY_ATTR_NAME_VALUE 主属性值
     * @return array
     */
    public function get_detail_all_arr($obj_data,$KEY_ATTR_NAME,$KEY_ATTR_NAME_VALUE){
        $attr = $obj_data["OBJ_NAME"].".".str_replace(".","",$KEY_ATTR_NAME);
        $arr = $this ->get_table_fld($attr,$this->session->userdata('org_id'));
        $all_detail_id_arr = $this -> get_all_detail_id($arr,$KEY_ATTR_NAME_VALUE,$obj_data["KEY_ATTR_FLD"]);
        return $all_detail_id_arr;
    }

}

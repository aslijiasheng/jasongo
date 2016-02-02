<?php
/**
 * Created by PhpStorm.
 * User: lee
 * Date: 14-9-10
 * Time: 下午3:31
 */

//创建
class Install extends ADMIN_Controller{
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        //echo $this->IsTable('LEE_SYS_MODEL');
        //$this->TableIsField('TC_USER','LEE_PASSWORD');
        echo "安装";
    }

    /**
     * 创建数据库
     */
    public function add_db(){
        //AT 添加表
        $this->AT_sys_model();
        //AF 添加字段
        $this->AF_user();
        $this->AF_menu();
        $this->AF_attr();
		//$this->AF_rel_profile();
        echo "语句创建完毕<br>";
        //$this->test_db();
        //echo "测试数据以添加<br>";
    }

    /**
     * update一些数据
     */
    public function UD_all(){
        $sql = "";
        $this->db->query($sql);
    }

    /**
     * AF: Add Field 添加字段
     */

    /**
     * tc_user用户表添加字段
     */
    public function AF_user(){
        if(!$this->TableIsField('tc_user','lee_password')){
            //新加一个密码 用于替换原来的密码规则
            $sql = "alter table tc_user add lee_password NVARCHAR2(128)";
            $this->db->query($sql);    
        }
    }
    
    
    /**
     * dd_attribute属性表添加字段
     */
    public function AF_attr(){
        if($this->TableIsField('dd_attribute','lee_is_query')){ //0显示 1不显示
            $sql = "alter table dd_attribute drop (lee_is_query)";
            $this->db->query($sql);
        }
        //添加一个是否查询显示【
        $sql = "alter table dd_attribute add lee_is_query NUMBER(6) DEFAULT 0 ";
        $this->db->query($sql);
        //线索列表
        $sql = "update dd_attribute set lee_is_query = 1 where obj_name = 'Lead'  and org_id = 1 and attr_name like '%.Department'";
        $this->db->query($sql);

        //对象来源ID和TYPE的关系属性名
        if($this->TableIsField('dd_attribute','lee_refer_obj_type')){ //0显示 1不显示
            $sql = "alter table dd_attribute drop (lee_refer_obj_type)";
            $this->db->query($sql);
        }
        $sql = "alter table dd_attribute add lee_refer_obj_type NVARCHAR2(128)";
        $this->db->query($sql);

        //客户的来源
        $sql = "update dd_attribute set lee_refer_obj_type = 'Account.SrcType' where obj_name = 'Account'  and org_id = 1 and attr_name = 'Account.SrcObject'";
        $this->db->query($sql);

        //计算型属性公式
        if($this->TableIsField('dd_attribute','lee_calc_formula')){
            $sql = "alter table dd_attribute drop (lee_calc_formula)";
            $this->db->query($sql);
        }
        $sql = "alter table dd_attribute add lee_calc_formula NVARCHAR2(255)";
        $this->db->query($sql);

        //是否为触发点
//        if($this->TableIsField('dd_attribute','lee_calc_cond')){
//            $sql = "alter table dd_attribute drop (lee_calc_cond)";
//            $this->db->query($sql);
//        }
        if($this->TableIsField('dd_attribute','lee_is_calc_point')){
            $sql = "alter table dd_attribute drop (lee_is_calc_point)";
            $this->db->query($sql);
        }
        $sql = "alter table dd_attribute add lee_is_calc_point NUMBER(6) DEFAULT 0";
        $this->db->query($sql);

        //计算的结果填充的属性
        if($this->TableIsField('dd_attribute','lee_calc_result_attr')){
            $sql = "alter table dd_attribute drop (lee_calc_result_attr)";
            $this->db->query($sql);
        }
        $sql = "alter table dd_attribute add lee_calc_result_attr NVARCHAR2(128)";
        $this->db->query($sql);
        
        //商机
        //汇总数量公式
        $sql="
update dd_attribute
set lee_calc_formula = '{INTSUM(#OpportunityItem.Quantity#)}', lee_is_calc_point = 0, lee_calc_result_attr = ''
where attr_name = N'Opportunity.Quantity'
";
        $this->db->query($sql);
        //单条数量公式
        $sql = "update dd_attribute
set lee_calc_formula = '', lee_is_calc_point = 1, lee_calc_result_attr = 'OpportunityItem.Amount,OpportunityItem.FinalPrice,Opportunity.Quantity'
where attr_name = N'OpportunityItem.Quantity'";
        $this->db->query($sql);
        //单条报价公式
        $sql = "update dd_attribute
        set lee_calc_formula = '#OpportunityItem.Amount#/#OpportunityItem.Quantity#', lee_is_calc_point = 1, lee_calc_result_attr = 'OpportunityItem.Amount'
        where attr_name = N'OpportunityItem.FinalPrice'";
        $this->db->query($sql);
        //单条金额
        $sql = "update dd_attribute
        set lee_calc_formula = '#OpportunityItem.FinalPrice#*#OpportunityItem.Quantity#', lee_is_calc_point = 1, lee_calc_result_attr = 'Opportunity.Amount,OpportunityItem.FinalPrice'
        where attr_name = N'OpportunityItem.Amount'";
        $this->db->query($sql);
        //汇总金额
        $sql = "update dd_attribute
        set lee_calc_formula = '{INTSUM(#OpportunityItem.Amount#)}', lee_is_calc_point = 0, lee_calc_result_attr = ''
        where attr_name = N'Opportunity.Amount'";
        $this->db->query($sql);
    }
    
	/**
	 * tc_user_rel_profile相关对象列表引用表
	 */
    public function AF_rel_profile() {
        if($this->TableIsField('tc_user_rel_profile','lee_condition')){
            $sql = "alter table tc_menu drop (lee_condition)";
            $this->db->query($sql);
        }
		//添加一个连接地址
        $sql = "alter table tc_user_rel_profile add lee_condition NVARCHAR2(300)";
        $this->db->query($sql);
        //执行方法初始化字段数据
	}

    /**
     * tc_menu菜单表添加字段
     */
    public function AF_menu(){
        if($this->TableIsField('tc_menu','lee_url')){
            $sql = "alter table tc_menu drop (lee_url)";
            $this->db->query($sql);
        }
        //添加一个连接地址
        $sql = "alter table tc_menu add lee_url NVARCHAR2(300)";
        $this->db->query($sql);

        if($this->TableIsField('tc_menu','lee_icon')){
            $sql = "alter table tc_menu drop (lee_icon)";
            $this->db->query($sql);
        }
        //添加一个图标编号
        $sql = "alter table tc_menu add lee_icon NVARCHAR2(100)";
        $this->db->query($sql);

        //--------------------------
        if($this->TableIsField('tc_menu','lee_stop_flag')){
            $sql = "alter table tc_menu drop (lee_stop_flag)";
            $this->db->query($sql);
        }
        //添加一个在新CRM里是否停用 默认为0 如果停用则为1
        $sql = "alter table tc_menu add lee_stop_flag NUMBER(6) default 0";
        $this->db->query($sql);


        //添加数据
        //线索
        $sql = "update tc_menu set lee_icon = 'fa fa-crosshairs',lee_url = '' where org_id = 1 and sys_type = 'CRM' and menu_id = 1";
        $this->db->query($sql);
        //线索列表
        $sql = "update tc_menu set lee_icon = 'fa fa-list',lee_url='www/objects/lists?obj_type=3' where org_id = 1 and sys_type = 'CRM' and menu_id = 3";
        $this->db->query($sql);
        //客户
        $sql = "update tc_menu set lee_icon = 'fa fa-user',lee_url = '' where org_id = 1 and sys_type = 'CRM' and menu_id = 9";
        $this->db->query($sql);
        //客户列表
        $sql = "update tc_menu set lee_icon = 'fa fa-list',lee_url='www/objects/lists?obj_type=1' where org_id = 1 and sys_type = 'CRM' and menu_id = 11";
        $this->db->query($sql);
        //市场管理
        $sql = "update tc_menu set lee_icon = 'fa fa-globe',lee_url = '' where org_id = 1 and sys_type = 'CRM' and menu_id = 25";
        $this->db->query($sql);
        //市场活动列表
        $sql = "update tc_menu set lee_icon = 'fa fa-list',lee_url = 'www/objects/lists?obj_type=8' where org_id = 1 and sys_type = 'CRM' and menu_id = 27";
        $this->db->query($sql);
        //合作伙伴合同
        $sql = "update tc_menu set lee_icon = 'fa fa-suitcase',lee_url = '' where org_id = 1 and sys_type = 'CRM' and menu_id = 3047";
        $this->db->query($sql);
        //合作伙伴合同列表
        $sql = "update tc_menu set lee_icon = 'fa fa-list',lee_url = 'www/objects/lists?obj_type=2003' where org_id = 1 and sys_type = 'CRM' and menu_id = 3048";
        $this->db->query($sql);
        //联系人
        $sql = "update tc_menu set lee_icon = 'fa fa-phone',lee_url = '' where org_id = 1 and sys_type = 'CRM' and menu_id = 17";
        $this->db->query($sql);
        //联系人列表
        $sql = "update tc_menu set lee_icon = 'fa fa-list',lee_url = 'www/objects/lists?obj_type=2' where org_id = 1 and sys_type = 'CRM' and menu_id = 19";
        $this->db->query($sql);
        //销售管理
        $sql = "update tc_menu set lee_icon = 'fa fa-money',lee_url = '' where org_id = 1 and sys_type = 'CRM' and menu_id = 41";
        $this->db->query($sql);
        //商机列表
        $sql = "update tc_menu set lee_icon = 'fa fa-list',lee_url = 'www/objects/lists?obj_type=4' where org_id = 1 and sys_type = 'CRM' and menu_id = 43";
        $this->db->query($sql);
        //销售机会明细
        $sql = "update tc_menu set lee_icon = 'fa fa-list-alt',lee_url = 'www/objects/lists?obj_type=23' where org_id = 1 and sys_type = 'CRM' and menu_id = 419";
        $this->db->query($sql);
        
        //订单
        $sql = "update tc_menu set lee_icon = 'fa fa-pencil',lee_url = '' where org_id = 1 and sys_type = 'CRM' and menu_id = 57";
        $this->db->query($sql);
        //订单列表
        $sql = "update tc_menu set lee_icon = 'fa fa-list',lee_url = 'www/objects/lists?obj_type=6' where org_id = 1 and sys_type = 'CRM' and menu_id = 59";
        $this->db->query($sql);
        //订单明细
        $sql = "update tc_menu set lee_icon = 'fa fa-list-alt',lee_url = 'www/objects/lists?obj_type=13' where org_id = 1 and sys_type = 'CRM' and menu_id = 416";
        $this->db->query($sql);

        //服务请求
        $sql = "update tc_menu set lee_icon = 'fa fa-comments-o',lee_url = '' where org_id = 1 and sys_type = 'CRM' and menu_id = 372";
        $this->db->query($sql);
        //服务请求列表
        $sql = "update tc_menu set lee_icon = 'fa fa-list',lee_url = 'www/objects/lists?obj_type=51' where org_id = 1 and sys_type = 'CRM' and menu_id = 373";
        $this->db->query($sql);

        //合同管理
        $sql = "update tc_menu set lee_icon = 'fa fa-file-text-o',lee_url = '' where org_id = 1 and sys_type = 'CRM' and menu_id = 3287";
        $this->db->query($sql);
        //合同列表
        $sql = "update tc_menu set lee_icon = 'fa fa-list',lee_url = 'www/objects/lists?obj_type=2004' where org_id = 1 and sys_type = 'CRM' and menu_id = 3293";
        $this->db->query($sql);
        //订单明细
        $sql = "update tc_menu set lee_icon = 'fa fa-list-alt',lee_url = 'www/objects/lists?obj_type=2005' where org_id = 1 and sys_type = 'CRM' and menu_id = 3316";
        $this->db->query($sql);


        //营收平台 
        $sql = "update tc_menu set lee_icon = 'fa fa-link',lee_url = 'http://npbi.ishopex.cn/' where org_id = 1 and sys_type = 'CRM' and menu_id = 3187";
        $this->db->query($sql);
        
        //合同平台 
        $sql = "update tc_menu set lee_icon = 'fa fa-link',lee_url = 'http://192.168.0.61:8036/' where org_id = 1 and sys_type = 'CRM' and menu_id = 3320";
        $this->db->query($sql);
        
        

        //停用一些菜单
        $sql = "update tc_menu set lee_stop_flag=1 where org_id = 1 and sys_type = 'CRM' and menu_id in (
372,
373,
89,
3299,
402,
403,
623,
3199,
3203,
3212,
3280,
3221,
3231,
3236,
3243,
3253,
3263,
3283,
3267,
3274,
7,
12,
14,
20,
21,
23,
28,
29,
31,
34,
36,
37,
39,
44,
46,
48,
49,
50,
60,
61,
62,
63,
64,
65,
92,
94,
96,
675,
671,
633,
76,
632,
102,
350,
351,
352,
711,
721,
374,
375,
4,
5,
404,
405,
406,
410,
415,
629,
630,
672,
674,
700,
3171,
445,
724,
729,
750,
768,
776,
782,
784,
793,
801,
804,
3041,
3046,
3056,
3066,
3169,
3353,
3304,
3314,
3176,
3024,
3027,
3097,
3141,
3133,
3145,
3149,
3151
        )";
        $this->db->query($sql);

    }

    /**
     * 创建系统模块关系表lee_sys_model
     */
    public function AT_sys_model(){
        if($this->IsTable('LEE_SYS_MODEL')){
            $sql = "DROP TABLE LEE_SYS_MODEL";
            $this->db->query($sql); 
        }
        $sql = "
        create table LEE_SYS_MODEL(
          SYS_ID        NUMBER(22) default 0 not null,
          SYS_NAME      NVARCHAR2(128) not null,
          SYS_URL       NVARCHAR2(300),
          PRIMARY KEY (SYS_ID)
        )
        ";
        $this->db->query($sql);
        //添加必要的数据
        $sql = "insert into LEE_SYS_MODEL (SYS_ID, SYS_NAME, SYS_URL) values (20000, 'KEY', 'admin')";
        $this->db->query($sql);
        $sql = "insert into LEE_SYS_MODEL (SYS_ID, SYS_NAME, SYS_URL) values (1, 'CRM', 'www')";
        $this->db->query($sql);
    }

    /**
     * 查询这个表里的某个字段是否存在
     * @param string $table_name: 表名 
     * @param string $field_name: 字段名
     * @return bool 
     */
    public function TableIsField($table_name,$field_name){
        //转化大写 防止写错
        $table_name = strtoupper($table_name);
        $field_name = strtoupper($field_name); 
        $sql = "select count(1) count from sys.user_tab_columns where table_name='".$table_name."' and column_name = '".$field_name."'";
        $data = $this->db->query($sql)->result_array();
        //p($data);
        if($data[0]['COUNT']>0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 查询这个表是否存在
     * @param string $table_name: 表名
     * @return bool 
     */
    public function IsTable($table_name){
        //转化大写 防止写错
        $table_name = strtoupper($table_name);
        $sql = "select count(1) count from sys.user_tab_columns where table_name='".$table_name."'";
        $data = $this->db->query($sql)->result_array();
        //p($data);
        if($data[0]['COUNT']>0){
            return true;
        }else{
            return false;
        }
    }
}
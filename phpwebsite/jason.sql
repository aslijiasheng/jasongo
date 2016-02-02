-- MySQL dump 10.16  Distrib 10.1.9-MariaDB, for osx10.11 (x86_64)
--
-- Host: localhost    Database: newpbi
-- ------------------------------------------------------
-- Server version	10.1.9-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `dd_action`
--

DROP TABLE IF EXISTS `dd_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dd_action` (
  `act_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '行为id',
  `act_name` char(255) DEFAULT NULL COMMENT '行为名称',
  `act_description` char(255) DEFAULT NULL COMMENT '描述',
  `obj_id` int(11) DEFAULT NULL COMMENT '对象ID',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `controller_template_id` int(11) DEFAULT NULL COMMENT '控制器模板ID',
  `view_template_id` int(11) DEFAULT NULL COMMENT '视图模板ID',
  PRIMARY KEY (`act_id`)
) ENGINE=InnoDB AUTO_INCREMENT=197 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dd_attribute`
--

DROP TABLE IF EXISTS `dd_attribute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dd_attribute` (
  `attr_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '属性ID',
  `attr_obj_id` int(11) NOT NULL COMMENT '所属对象ID',
  `attr_name` varchar(255) NOT NULL COMMENT '属性名称',
  `attr_label` varchar(255) NOT NULL COMMENT '属性中文标签',
  `attr_type` int(11) NOT NULL COMMENT '字段类型',
  `attr_field_name` varchar(255) NOT NULL COMMENT '字段类型',
  `attr_quote_id` int(11) NOT NULL COMMENT '引用对象ID',
  PRIMARY KEY (`attr_id`)
) ENGINE=MyISAM AUTO_INCREMENT=661 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dd_attrtype`
--

DROP TABLE IF EXISTS `dd_attrtype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dd_attrtype` (
  `attrtype_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '属性类型ID',
  `attrtype_name` varchar(128) NOT NULL COMMENT '属性类型名称',
  `attrtype_view_template` varchar(128) NOT NULL COMMENT '查看页面模板',
  `attrtype_edit_template` varchar(128) NOT NULL COMMENT '编辑页面模板',
  `attrtype_select_template` varchar(128) NOT NULL COMMENT '查询条件模板',
  `attrtype_field_type` varchar(128) NOT NULL COMMENT '数据库字段类型',
  PRIMARY KEY (`attrtype_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dd_auth_menu`
--

DROP TABLE IF EXISTS `dd_auth_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dd_auth_menu` (
  `auth_m_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增Id',
  `role_id` int(10) unsigned NOT NULL COMMENT '角色Id',
  `auth_m_json` varchar(256) NOT NULL COMMENT '是否被允许',
  PRIMARY KEY (`auth_m_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dd_choose_attrs`
--

DROP TABLE IF EXISTS `dd_choose_attrs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dd_choose_attrs` (
  `dca_id` int(11) NOT NULL AUTO_INCREMENT,
  `choose_attr` char(100) DEFAULT NULL,
  `obj_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `page_type` char(10) DEFAULT NULL COMMENT '页面类型',
  PRIMARY KEY (`dca_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dd_config`
--

DROP TABLE IF EXISTS `dd_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dd_config` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `config_datetime` datetime NOT NULL COMMENT '定时任务上次执行时间',
  `config_status` tinyint(4) NOT NULL COMMENT '是否开启(1 开启 0未开启)',
  `config_difftime` int(11) NOT NULL COMMENT '间隔时间',
  PRIMARY KEY (`config_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dd_detail`
--

DROP TABLE IF EXISTS `dd_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dd_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) NOT NULL,
  `detail_obj_id` int(11) NOT NULL,
  `detail_attr_id` int(9) NOT NULL,
  `type_id` int(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dd_enum`
--

DROP TABLE IF EXISTS `dd_enum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dd_enum` (
  `enum_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '枚举ID',
  `attr_id` int(11) DEFAULT NULL COMMENT '属性ID',
  `enum_name` char(255) DEFAULT NULL COMMENT '枚举名',
  `enum_key` varchar(255) DEFAULT NULL COMMENT '枚举值',
  `disp_order` int(11) DEFAULT '0' COMMENT '显示顺序',
  `is_default` tinyint(4) DEFAULT '1' COMMENT '是否为默认选项',
  `system_flag` tinyint(4) DEFAULT '0' COMMENT '是否为系统枚举',
  PRIMARY KEY (`enum_id`)
) ENGINE=InnoDB AUTO_INCREMENT=832 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dd_icon`
--

DROP TABLE IF EXISTS `dd_icon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dd_icon` (
  `icon_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '图标ID',
  `icon_name` varchar(128) NOT NULL COMMENT '图标名称',
  `icon_code` varchar(128) NOT NULL COMMENT '字符编码',
  PRIMARY KEY (`icon_id`)
) ENGINE=MyISAM AUTO_INCREMENT=482 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dd_layout`
--

DROP TABLE IF EXISTS `dd_layout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dd_layout` (
  `layout_id` int(11) NOT NULL AUTO_INCREMENT,
  `layout_json` text COMMENT '内容json数组',
  `obj_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `layout_type` char(10) DEFAULT NULL COMMENT '页面类型',
  PRIMARY KEY (`layout_id`)
) ENGINE=InnoDB AUTO_INCREMENT=195 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dd_menu`
--

DROP TABLE IF EXISTS `dd_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dd_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `menu_name` char(255) DEFAULT NULL COMMENT '菜单名称',
  `menu_uid` int(11) DEFAULT NULL COMMENT '父级菜单ID',
  `menu_url` char(255) DEFAULT NULL COMMENT '菜单路径',
  `menu_label` varchar(30) NOT NULL COMMENT '菜单标签',
  `menu_is_js` tinyint(4) DEFAULT '0' COMMENT '是否定义JS方法(0:否; 1:是)',
  `menu_js_content` text COMMENT 'JS内容',
  `menu_icon` char(255) DEFAULT NULL COMMENT '菜单图标',
  `menu_path` varchar(255) DEFAULT NULL COMMENT '菜单层级',
  `menu_order` int(3) DEFAULT '0' COMMENT '菜单排序',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dd_nextid`
--

DROP TABLE IF EXISTS `dd_nextid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dd_nextid` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `attr_name` varchar(128) NOT NULL COMMENT '属性名称',
  `nextid` int(11) NOT NULL COMMENT '当前最大ID',
  `last_time` datetime NOT NULL COMMENT '上次执行时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dd_number`
--

DROP TABLE IF EXISTS `dd_number`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dd_number` (
  `order_type` int(3) NOT NULL COMMENT '订单类型',
  `order_number` varchar(128) DEFAULT NULL COMMENT '订单编号',
  PRIMARY KEY (`order_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dd_object`
--

DROP TABLE IF EXISTS `dd_object`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dd_object` (
  `obj_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '对象ID',
  `obj_uid` int(11) NOT NULL DEFAULT '0' COMMENT '父级对象ID',
  `obj_name` varchar(128) NOT NULL COMMENT '对象名称',
  `obj_label` varchar(128) NOT NULL COMMENT '中文标签',
  `obj_icon` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '对象图标',
  `is_obj_type` enum('0','1') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0:不能定义对象类型；1:可以定义对象类型',
  `is_ref_obj` enum('0','1') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0:对象不能被引用；1:对象可以被引用',
  `is_detail` enum('0','1') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0:不包含明细；1:是否包含明细',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`obj_id`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dd_task`
--

DROP TABLE IF EXISTS `dd_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dd_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(128) DEFAULT NULL COMMENT '编号',
  `start_time` datetime DEFAULT NULL COMMENT '开始时间',
  `end_time` datetime DEFAULT NULL COMMENT '结束时间',
  `status` enum('true','false') NOT NULL DEFAULT 'false' COMMENT '状态：成功/失败',
  `last_time` datetime DEFAULT NULL COMMENT '更新时间',
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dd_template`
--

DROP TABLE IF EXISTS `dd_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dd_template` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '模板ID',
  `template_name` char(255) DEFAULT NULL COMMENT '模板名称',
  `template_path` char(255) DEFAULT NULL COMMENT '模板路径',
  `template_type` char(255) DEFAULT NULL COMMENT '模板类型',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dd_type`
--

DROP TABLE IF EXISTS `dd_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dd_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` char(255) DEFAULT NULL COMMENT '类型名称',
  `obj_id` int(11) DEFAULT NULL COMMENT '对象ID',
  `type_label` varchar(128) NOT NULL COMMENT '类型菜单标签',
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `demo`
--

DROP TABLE IF EXISTS `demo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `demo` (
  `order_number` varchar(255) NOT NULL,
  `book_no` varchar(255) DEFAULT NULL,
  `tttt` int(5) unsigned NOT NULL DEFAULT '0',
  `ttttd` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_number`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gii_auth`
--

DROP TABLE IF EXISTS `gii_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gii_auth` (
  `au_id` int(11) NOT NULL AUTO_INCREMENT,
  `au_name` varchar(30) NOT NULL,
  `au_password` char(50) NOT NULL,
  PRIMARY KEY (`au_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_account`
--

DROP TABLE IF EXISTS `tc_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_account` (
  `account_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `account_name` varchar(128) DEFAULT NULL COMMENT '名称',
  `account_leader` varchar(128) DEFAULT NULL COMMENT '负责人',
  `account_update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `account_account_shopexid` varchar(128) DEFAULT NULL COMMENT '商业id',
  `account_create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `account_telephone` varchar(50) DEFAULT NULL COMMENT '电话',
  `type_id` int(11) DEFAULT NULL COMMENT '客户类型',
  `account_email` varchar(50) DEFAULT NULL COMMENT '客户邮箱',
  `account_department` int(11) DEFAULT NULL COMMENT '所属部门',
  `account_is_u8` int(11) DEFAULT NULL COMMENT '是否同步U8',
  `account_pbi_id` varchar(225) DEFAULT NULL COMMENT '老营收ID',
  `account_name_u8` varchar(225) DEFAULT NULL COMMENT '客户名称（U8）',
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=69735 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_account1`
--

DROP TABLE IF EXISTS `tc_account1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_account1` (
  `account_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `account_name` varchar(128) DEFAULT NULL COMMENT '名称',
  `account_path` varchar(128) DEFAULT NULL COMMENT '级别',
  `account_leader` varchar(128) DEFAULT NULL COMMENT '负责人',
  `account_update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `account_account_shopexid` varchar(128) DEFAULT NULL COMMENT '商业id',
  `account_create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `account_telephone` varchar(50) DEFAULT NULL COMMENT '电话',
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_acct_open`
--

DROP TABLE IF EXISTS `tc_acct_open`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_acct_open` (
  `acct_open_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `acct_open_account` int(11) DEFAULT NULL COMMENT '客户',
  `acct_open_goods_acct_code` varchar(225) DEFAULT NULL COMMENT '商品户号',
  `acct_open_goods_name` varchar(128) DEFAULT NULL COMMENT '商品名称',
  `acct_open_goods_code` varchar(128) DEFAULT NULL COMMENT '商品编码',
  `acct_open_product_basic_acct_code` varchar(128) DEFAULT NULL COMMENT '基础产品户号',
  `acct_open_product_basic_name` varchar(128) DEFAULT NULL COMMENT '基础产品名称',
  `acct_open_product_basic_code` varchar(128) DEFAULT NULL COMMENT '基础产品编码',
  `acct_open_service_startdate` datetime DEFAULT NULL COMMENT '服务开始时间',
  `acct_open_service_enddate` datetime DEFAULT NULL COMMENT '服务结束时间',
  `acct_open_is_invalid` int(11) DEFAULT NULL COMMENT '是否作废',
  PRIMARY KEY (`acct_open_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_api_del_bak`
--

DROP TABLE IF EXISTS `tc_api_del_bak`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_api_del_bak` (
  `order_id` varchar(128) DEFAULT NULL,
  `salespay` text,
  `rebate` text,
  `invoice` text,
  `order_d` text,
  `refund` text,
  `rel_order_order` text,
  `order` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_api_log`
--

DROP TABLE IF EXISTS `tc_api_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_api_log` (
  `api_log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `api_log_addtime` datetime DEFAULT NULL COMMENT '创建时间',
  `api_log_method` varchar(128) DEFAULT NULL COMMENT '操作方法',
  `api_log_data` text COMMENT '原始数据',
  `api_log_status` int(11) DEFAULT NULL COMMENT '状态',
  `api_log_result` varchar(225) DEFAULT NULL COMMENT '返回结果',
  `api_log_order_number` varchar(128) DEFAULT NULL COMMENT '订单编号',
  PRIMARY KEY (`api_log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43512 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_api_log_bak`
--

DROP TABLE IF EXISTS `tc_api_log_bak`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_api_log_bak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `res` varchar(10) DEFAULT NULL,
  `msg` varchar(255) DEFAULT NULL COMMENT '问题数据',
  `atime` datetime DEFAULT NULL COMMENT '日志记录时间',
  `api_log_addtime` datetime DEFAULT NULL COMMENT '创建时间',
  `api_log_method` varchar(128) DEFAULT NULL COMMENT '操作方法',
  `api_log_data` text COMMENT '原始数据',
  `api_log_status` int(11) DEFAULT NULL COMMENT '状态',
  `api_log_result` varchar(225) DEFAULT NULL COMMENT '返回结果',
  `api_log_order_number` varchar(128) DEFAULT NULL COMMENT '订单编号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24953 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_api_old_log`
--

DROP TABLE IF EXISTS `tc_api_old_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_api_old_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `res` varchar(10) DEFAULT NULL,
  `msg` varchar(255) DEFAULT NULL COMMENT '问题数据',
  `atime` datetime DEFAULT NULL COMMENT '日志记录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48539 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_applyopen`
--

DROP TABLE IF EXISTS `tc_applyopen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_applyopen` (
  `applyopen_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `applyopen_create_user` int(11) DEFAULT NULL COMMENT '创建人',
  `applyopen_create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `applyopen_open_info` text COMMENT '开通参数',
  `applyopen_return_info` text COMMENT '返回参数',
  `applyopen_order_d_id` int(11) DEFAULT NULL COMMENT '所属订单明细',
  PRIMARY KEY (`applyopen_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4980 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_bgrelation`
--

DROP TABLE IF EXISTS `tc_bgrelation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_bgrelation` (
  `bgrelation_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `bgrelation_bundle_id` int(11) DEFAULT NULL COMMENT '组合ID（父商品ID）',
  `bgrelation_goods_id` int(11) DEFAULT NULL COMMENT '被捆绑商品的ID（子ID）',
  `bgrelation_goods_price_id` int(11) DEFAULT NULL COMMENT '商品价格ID',
  `bgrelation_group_price` decimal(20,3) DEFAULT NULL COMMENT '组合价格',
  PRIMARY KEY (`bgrelation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=723 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_books`
--

DROP TABLE IF EXISTS `tc_books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_books` (
  `books_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `books_company` int(11) NOT NULL COMMENT '公司',
  `books_bank_account` int(11) DEFAULT NULL COMMENT '账户',
  `books_adate` datetime DEFAULT NULL COMMENT '实际到账/出账日期',
  `books_bdate` datetime DEFAULT NULL COMMENT '确认到账/出账日期',
  `books_trade_no` varchar(128) DEFAULT NULL COMMENT '交易号',
  `books_attn_id` int(11) DEFAULT NULL COMMENT '经办人ID',
  `books_department_id` int(11) DEFAULT NULL COMMENT '部门ID',
  `books_memo` varchar(225) DEFAULT NULL COMMENT '备注',
  `books_customer_info` varchar(128) DEFAULT NULL COMMENT '客户信息',
  `books_sale_no` varchar(128) DEFAULT NULL COMMENT '销售记录号',
  `books_order_no` varchar(128) DEFAULT NULL COMMENT '订单号',
  `books_invoice_track` int(11) DEFAULT NULL COMMENT '发票追踪',
  `books_rate` decimal(20,3) DEFAULT NULL COMMENT '汇率',
  `books_rmb` decimal(20,3) DEFAULT NULL COMMENT '折合RMB',
  `books_state` int(11) DEFAULT NULL COMMENT '状态',
  `books_createtime` datetime DEFAULT NULL COMMENT '添加时间',
  `books_updatetime` datetime DEFAULT NULL COMMENT '更新时间',
  `books_Note` varchar(225) DEFAULT NULL COMMENT '摘要',
  `books_debit_amount` decimal(20,3) DEFAULT NULL COMMENT '借方发生额',
  `books_Credit_amount` decimal(20,3) DEFAULT NULL COMMENT '贷方发生额',
  `books_cashier` int(11) DEFAULT NULL COMMENT '出纳',
  `books_accounting` int(11) DEFAULT NULL COMMENT '会计',
  `books_sales_no` varchar(128) DEFAULT NULL COMMENT '销售号',
  `books_other_memo` varchar(225) DEFAULT NULL COMMENT '其他备注',
  `books_number` varchar(128) DEFAULT NULL COMMENT '日记账编号',
  `books_subsidiary` int(11) DEFAULT NULL COMMENT '所属子公司',
  PRIMARY KEY (`books_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13566 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_department`
--

DROP TABLE IF EXISTS `tc_department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_department` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `department_name` varchar(128) NOT NULL COMMENT '部门名称',
  `department_uid` int(11) NOT NULL COMMENT '上级部门',
  `department_code` varchar(128) NOT NULL COMMENT '部门编码',
  `department_treepath` varchar(225) NOT NULL COMMENT '树路径',
  `department_treelevel` int(11) NOT NULL COMMENT '级别',
  `department_author` int(11) DEFAULT NULL COMMENT '部门负责人',
  `department_is_u8` int(11) DEFAULT NULL COMMENT '是否同步U8',
  `department_u8_code` varchar(128) DEFAULT NULL COMMENT '对应U8编码',
  `department_crm_code` varchar(128) DEFAULT NULL COMMENT '对应CRM编码',
  PRIMARY KEY (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=659 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_express_mail`
--

DROP TABLE IF EXISTS `tc_express_mail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_express_mail` (
  `express_id` int(11) NOT NULL AUTO_INCREMENT,
  `express_from_user_id` varchar(20) NOT NULL COMMENT '发送邮件人',
  `express_to_user_id` varchar(20) NOT NULL COMMENT '发送到邮件人',
  `express_create_user_id` varchar(20) NOT NULL COMMENT '创建人',
  `express_create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `express_to_express_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '快件到货时间',
  `express_take_status` int(1) NOT NULL COMMENT '0 待发 1 已发  2 发送失败 3 重发',
  PRIMARY KEY (`express_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='取件通知表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_express_take`
--

DROP TABLE IF EXISTS `tc_express_take`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_express_take` (
  `express_take_id` int(11) NOT NULL AUTO_INCREMENT,
  `express_take_express_id` int(11) NOT NULL COMMENT '取件ID',
  `express_take_user_id` varchar(20) NOT NULL COMMENT '取件人',
  `express_take_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '取件时间',
  PRIMARY KEY (`express_take_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='取件登记表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_goods`
--

DROP TABLE IF EXISTS `tc_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_goods` (
  `goods_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `goods_code` varchar(128) NOT NULL COMMENT '商品编码',
  `goods_name` varchar(128) NOT NULL COMMENT '商品名称',
  `goods_type` int(11) NOT NULL COMMENT '是标准商品或者捆绑商品（0：标准商品，1：捆绑商品）',
  `goods_desc` varchar(225) NOT NULL COMMENT '商品简介',
  `goods_visable` int(11) NOT NULL COMMENT '是否启用（1：是，0：否）',
  `goods_is_trail` int(11) NOT NULL COMMENT '是否可试用（1：是，0：否）',
  `goods_is_sale` int(11) NOT NULL COMMENT '是否允许单独售卖（1：是，0：否）',
  `goods_sale_type` varchar(128) NOT NULL COMMENT '售卖方分类(1901：直销团队；1902：渠道团队；1903：协运营团队；1904：电商人才中心；1905：海外及政府团队；1906：开发伙伴部；1907：大客户协调部；)',
  `goods_trail_days` int(11) NOT NULL COMMENT '试用天数（天）',
  `goods_check_pay` int(11) NOT NULL COMMENT '计费方式（1：周期性，0：一次性）',
  `goods_is_new` int(11) NOT NULL,
  `goods_utime` datetime NOT NULL COMMENT '商品信息更新时间',
  `goods_price` decimal(20,2) DEFAULT NULL COMMENT '价格（捆绑商品）',
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1135 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_goods_price`
--

DROP TABLE IF EXISTS `tc_goods_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_goods_price` (
  `goods_price_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `goods_price_goods_id` int(11) NOT NULL COMMENT '商品ID',
  `goods_price_code` varchar(128) NOT NULL COMMENT '价格编号',
  `goods_price_visable` int(11) DEFAULT NULL COMMENT '是否启用',
  `goods_price_start_price` decimal(20,3) DEFAULT NULL COMMENT '一次性计费的价格',
  `goods_price_cycle_days` int(11) DEFAULT NULL COMMENT '周期性计费的周期',
  `goods_price_cycle_unit` int(11) DEFAULT NULL COMMENT '周期单位（1401：年，1402：月，1403：天）',
  `goods_price_cycle_price` decimal(20,3) DEFAULT NULL COMMENT '周期性计费的价格',
  `goods_price_effective_days` int(11) DEFAULT NULL COMMENT '有效期时间（天）',
  PRIMARY KEY (`goods_price_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1104 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_gprelation`
--

DROP TABLE IF EXISTS `tc_gprelation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_gprelation` (
  `gprelation_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `gprelation_goods_id` int(11) NOT NULL COMMENT '商品ID',
  `gprelation_product_tech_id` int(11) NOT NULL COMMENT '技术产品ID',
  `gprelation_product_basic_id` int(11) NOT NULL COMMENT '基础产品ID',
  `gprelation_rate` int(11) NOT NULL COMMENT '分配比率',
  PRIMARY KEY (`gprelation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=975 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_invoice`
--

DROP TABLE IF EXISTS `tc_invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_invoice` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `invoice_sale_id` int(11) NOT NULL COMMENT '销售ID',
  `invoice_order_id` int(11) NOT NULL COMMENT '订单ID',
  `invoice_type` int(11) NOT NULL COMMENT '票据类型',
  `invoice_invoice_no` varchar(128) NOT NULL COMMENT '票据号码',
  `invoice_customer_type` int(11) NOT NULL DEFAULT '1002' COMMENT '客户类型',
  `invoice_title` varchar(128) NOT NULL COMMENT '票据抬头',
  `invoice_address` varchar(128) NOT NULL COMMENT '地址',
  `invoice_tel` varchar(128) NOT NULL COMMENT '电话',
  `invoice_bank` varchar(128) NOT NULL COMMENT '开户行',
  `invoice_content` text NOT NULL COMMENT '票据内容',
  `invoice_receipt_content` varchar(256) NOT NULL,
  `invoice_amount` decimal(20,2) unsigned DEFAULT NULL COMMENT '票据金额',
  `invoice_addtime` datetime NOT NULL COMMENT '申请开票时间',
  `invoice_untime` datetime NOT NULL COMMENT '作废时间',
  `invoice_memo` varchar(225) NOT NULL COMMENT '开票备注',
  `invoice_taxpayer` varchar(128) NOT NULL COMMENT '纳税人识别号',
  `type_id` int(11) NOT NULL COMMENT '所属类型',
  `invoice_kp_type` int(11) NOT NULL COMMENT '开票类型，区分开票/预开票和财务自主开票',
  `invoice_uninvoice_status` int(4) NOT NULL DEFAULT '1002' COMMENT '申请作废状态',
  `invoice_apply_uninvoice_note` text NOT NULL COMMENT '申请作废备注',
  `invoice_process_status` int(11) NOT NULL DEFAULT '1002' COMMENT '预开票审核状态',
  `invoice_process_note` text NOT NULL COMMENT '预开票审核备注',
  `invoice_process_time` date NOT NULL COMMENT '审核时间',
  `invoice_do_status` int(11) NOT NULL DEFAULT '1002' COMMENT '开票状态',
  `invoice_do_note` text NOT NULL COMMENT '开票备注',
  `invoice_do_time` datetime NOT NULL COMMENT '开票时间',
  `invoice_code` varchar(128) NOT NULL,
  `invoice_uninvoice_note` text NOT NULL COMMENT '作废备注',
  `invoice_apply_untime` date NOT NULL COMMENT '申请作废时间',
  `invoice_account` varchar(225) DEFAULT NULL COMMENT '帐号',
  `invoice_reviewer` int(11) DEFAULT NULL COMMENT '业务负责人',
  `invoice_is_uninvoice_note` text COMMENT '作废备注',
  `invoice_number` varchar(225) DEFAULT NULL COMMENT '票据编号',
  `invoice_status` int(11) DEFAULT NULL COMMENT '票据状态',
  `invoice_content_txt` text COMMENT '票据内容（TXT）',
  `invoice_subsidiary` int(11) DEFAULT NULL COMMENT '所属子公司',
  PRIMARY KEY (`invoice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6853 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_message`
--

DROP TABLE IF EXISTS `tc_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_message` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `message_owner` int(11) DEFAULT NULL COMMENT '负责人',
  `message_module_id` int(11) DEFAULT NULL COMMENT '模块id',
  `message_type` int(11) DEFAULT NULL COMMENT '操作',
  `message_url` varchar(128) DEFAULT NULL COMMENT '链接地址',
  `message_status` int(11) DEFAULT '1001' COMMENT '待办状态',
  `message_module` varchar(128) DEFAULT NULL COMMENT '所属模块',
  `message_department` int(11) DEFAULT '1',
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17855 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_message_remind`
--

DROP TABLE IF EXISTS `tc_message_remind`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_message_remind` (
  `message_remind_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `message_remind_role_id` int(11) DEFAULT NULL COMMENT '角色id',
  `message_remind_status` int(11) DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`message_remind_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_open_apply`
--

DROP TABLE IF EXISTS `tc_open_apply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_open_apply` (
  `open_apply_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `tc_open_apply_account` int(11) DEFAULT NULL COMMENT '客户',
  `tc_open_apply_order` int(11) DEFAULT NULL COMMENT '订单',
  `tc_open_apply_goods_acct_code` varchar(128) DEFAULT NULL COMMENT '商品户号',
  `tc_open_apply_goods_name` varchar(128) DEFAULT NULL COMMENT '商品名称',
  `tc_open_apply_goods_code` varchar(128) DEFAULT NULL COMMENT '商品编码',
  `open_apply_product_basic_acct_code` varchar(128) DEFAULT NULL COMMENT '基础产品户号',
  `open_apply_product_basic_name` varchar(128) DEFAULT NULL COMMENT '基础产品名称',
  `open_apply_product_basic_code` varchar(128) DEFAULT NULL COMMENT '基础产品编码',
  `open_apply_apply_status` int(11) DEFAULT NULL COMMENT '开通状态',
  `open_apply_owner_user` int(11) DEFAULT NULL COMMENT '所有者',
  `open_apply_department` int(11) DEFAULT NULL COMMENT '所属部门',
  `open_apply_create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `open_apply_modify_time` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`open_apply_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_order`
--

DROP TABLE IF EXISTS `tc_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_number` varchar(128) DEFAULT NULL COMMENT '订单编号',
  `order_create_user_id` int(11) DEFAULT NULL COMMENT '订单创建者id',
  `order_create_time` datetime DEFAULT NULL COMMENT '订单创建时间',
  `order_department` int(11) DEFAULT NULL COMMENT '所属部门',
  `order_owner` int(11) DEFAULT NULL COMMENT '所属销售',
  `order_account` int(11) DEFAULT NULL COMMENT '所属客户',
  `order_agent` int(11) DEFAULT NULL COMMENT '所属代理商',
  `order_agreement_no` varchar(225) DEFAULT NULL COMMENT '合同编号',
  `order_amount` decimal(20,2) DEFAULT NULL COMMENT '订单金额',
  `order_finance` int(11) DEFAULT NULL COMMENT '财务人员',
  `order_agreement_name` varchar(128) DEFAULT NULL COMMENT '合同名称',
  `order_review` int(11) DEFAULT NULL COMMENT '业务主管',
  `order_nreview` int(11) DEFAULT NULL COMMENT '内审人员',
  `order_if_renew` int(11) DEFAULT NULL COMMENT '是否续费订单',
  `type_id` int(11) DEFAULT NULL COMMENT '所属类型',
  `order_rebate_amount` decimal(20,3) DEFAULT NULL COMMENT '可返点总金额',
  `order_cancel_node` int(3) DEFAULT '0' COMMENT '作废节点',
  `order_relation_order_id` text COMMENT '关联销售订单',
  `order_transfer_name` varchar(225) DEFAULT NULL COMMENT '款项名称',
  `order_change_out` int(11) DEFAULT NULL COMMENT '转出方',
  `order_change_into` int(11) DEFAULT NULL COMMENT '转入方',
  `order_out_examine` int(11) DEFAULT NULL COMMENT '转出方主管审核',
  `order_change_into_money` decimal(20,3) DEFAULT NULL COMMENT '转入金额',
  `order_transfer_state` int(11) DEFAULT NULL COMMENT '内划状态',
  `order_transfer_date` date DEFAULT NULL COMMENT '确认划款日期',
  `order_is_cancel` int(11) DEFAULT '1001' COMMENT '订单是否作废',
  `order_PassID` int(11) DEFAULT NULL COMMENT 'PassID',
  `order_customer_type` int(11) DEFAULT NULL COMMENT '客户类型',
  `order_rmb_type` int(11) DEFAULT NULL COMMENT '返款类型',
  `order_rmb_amount` decimal(20,3) DEFAULT NULL COMMENT '申请支出金额',
  `order_rmb_handlingcharge` int(11) DEFAULT NULL COMMENT '手续费承担方',
  `order_rmb_pay_method` int(11) DEFAULT NULL COMMENT '打款方式',
  `order_rmb_bank` int(11) DEFAULT NULL COMMENT '汇款银行',
  `order_state` int(11) DEFAULT NULL COMMENT '订单状态',
  `order_account_type` int(11) DEFAULT NULL COMMENT '账户类型',
  `order_account_name` varchar(128) DEFAULT NULL COMMENT '账户名称',
  `order_account_number` int(11) DEFAULT NULL COMMENT '银行账号',
  `order_bank_name` varchar(128) DEFAULT NULL COMMENT '开户银行名称',
  `order_bank_country` int(11) DEFAULT NULL COMMENT '开户银行所在城市',
  `order_payment_number` varchar(128) DEFAULT NULL COMMENT '支付宝账号',
  `order_rmb_reason` varchar(225) DEFAULT NULL COMMENT '返款原因',
  `order_rmb_person` int(11) DEFAULT NULL COMMENT '选择审核人员',
  `order_rmb_user` int(11) DEFAULT NULL COMMENT '经手人',
  `order_rmb_finance_id` int(11) DEFAULT NULL COMMENT '选择审核财务',
  `order_prepay_company` int(11) DEFAULT NULL COMMENT '公司名称',
  `order_sale_source` int(11) DEFAULT NULL COMMENT '销售项目',
  `order_transfer_reason` text COMMENT '划款原因',
  `order_rebates_type` int(11) DEFAULT NULL COMMENT '返点订单类型',
  `order_relation_order_all` text COMMENT '关联销售订单（多选）',
  `order_rebates_state` int(11) DEFAULT NULL COMMENT '返点订单状态',
  `order_rebates_remark` text COMMENT '返点订单备注',
  `order_deposit_remark` varchar(225) DEFAULT NULL COMMENT '预收款备注',
  `order_deposit_paytype` int(11) DEFAULT NULL COMMENT '款项类型',
  `order_source` int(11) DEFAULT NULL COMMENT '订单来源',
  `order_aid` varchar(128) DEFAULT NULL COMMENT 'api order_id',
  `order_typein` int(11) DEFAULT NULL COMMENT '录入人（合同）',
  `order_cancel_order_id` int(11) DEFAULT NULL COMMENT '作废变更关联订单  ',
  `order_cost_400` decimal(20,2) DEFAULT NULL COMMENT '成本400电话',
  `order_cost_design` decimal(20,2) DEFAULT NULL COMMENT '成本设计装修',
  `order_cost_dsf` decimal(20,2) DEFAULT NULL COMMENT '成本第三方软件硬件',
  `order_cost_outsourcing` decimal(20,2) DEFAULT NULL COMMENT '成本外包',
  `order_cost_disk` decimal(20,2) DEFAULT NULL COMMENT '成本带宽/硬盘',
  `order_cost_sms` decimal(20,2) DEFAULT NULL COMMENT '成本短信',
  `order_cost_domain` decimal(20,2) DEFAULT NULL COMMENT '成本域名',
  `order_is_crm` int(11) DEFAULT NULL COMMENT '是否同步CRM',
  `order_agreement_note` text COMMENT '合同备注',
  `order_remarks` text COMMENT '订单备注',
  `order_subsidiary` int(11) DEFAULT NULL COMMENT '所属子公司',
  `order_tripartite_agreement` varchar(255) DEFAULT NULL COMMENT '第三方客户名称',
  PRIMARY KEY (`order_id`),
  KEY `order_number` (`order_number`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12923 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_order_adjunct`
--

DROP TABLE IF EXISTS `tc_order_adjunct`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_order_adjunct` (
  `order_adjunct_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_adjunct_name` varchar(128) DEFAULT NULL COMMENT '附件名称',
  `order_adjunct_url` varchar(225) DEFAULT NULL COMMENT '下载地址',
  `order_adjunct_atime` datetime DEFAULT NULL COMMENT '创建时间',
  `order_adjunct_order_id` int(11) DEFAULT NULL COMMENT '订单ID',
  PRIMARY KEY (`order_adjunct_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1952 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_order_d`
--

DROP TABLE IF EXISTS `tc_order_d`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_order_d` (
  `order_d_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_d_goods_name` varchar(128) DEFAULT NULL COMMENT '商品名称',
  `order_d_goods_code` varchar(128) DEFAULT NULL COMMENT '商品编码',
  `order_d_product_basic_name` varchar(128) DEFAULT NULL COMMENT '基础产品名称',
  `order_d_product_basic_code` varchar(128) DEFAULT NULL COMMENT '基础产品编码',
  `order_d_product_basic_servicecycle` varchar(128) DEFAULT NULL COMMENT '服务周期',
  `order_d_goods_primecost` decimal(20,2) DEFAULT NULL COMMENT '商品原价',
  `order_d_goods_disc` decimal(20,2) DEFAULT NULL COMMENT '商品折后价',
  `order_d_product_basic_style` int(11) DEFAULT NULL COMMENT '开通状态',
  `order_d_order_id` int(11) DEFAULT NULL COMMENT '所属订单',
  `order_d_product_basic_primecost` decimal(20,2) DEFAULT NULL COMMENT '基础产品原价',
  `order_d_product_basic_disc` decimal(20,2) DEFAULT NULL COMMENT '基础产品折后价',
  `order_d_product_rate` int(11) DEFAULT NULL COMMENT '基础产品价格比例',
  `order_d_number` varchar(225) DEFAULT NULL COMMENT '明细编号',
  `order_d_list` int(11) DEFAULT NULL COMMENT '列表',
  `order_d_idc_cost` decimal(20,2) DEFAULT NULL COMMENT 'IDC成本',
  `order_d_sms_cost` decimal(20,2) DEFAULT NULL COMMENT '短信成本',
  `order_d_domain_cost` decimal(20,2) DEFAULT NULL COMMENT '域名/备案成本',
  `order_d_open_startdate` datetime DEFAULT NULL COMMENT '开通开始时间',
  `order_d_open_enddate` datetime DEFAULT NULL COMMENT '开通结束时间',
  `order_d_goods_acct_code` varchar(255) DEFAULT NULL,
  `order_d_product_basic_acct_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`order_d_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14355 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_order_record`
--

DROP TABLE IF EXISTS `tc_order_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_order_record` (
  `order_record_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `cancel_order_id` int(11) DEFAULT NULL COMMENT '订单ID',
  `cancel_create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `order_record_operation` int(11) DEFAULT NULL COMMENT '操作',
  `cancel_remark` text COMMENT '备注',
  `cancel_user_id` int(11) DEFAULT NULL COMMENT '操作人ID',
  PRIMARY KEY (`order_record_id`)
) ENGINE=InnoDB AUTO_INCREMENT=219 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_order_shift`
--

DROP TABLE IF EXISTS `tc_order_shift`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_order_shift` (
  `order_shift_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_shift_order_id` int(11) DEFAULT NULL COMMENT '订单id',
  `order_shift_old_data` varchar(225) DEFAULT NULL COMMENT '旧数据',
  `order_shift_new_data` varchar(225) DEFAULT NULL COMMENT '新数据',
  `order_shift_update_time` datetime DEFAULT NULL COMMENT '修改时间',
  `order_shift_title` varchar(128) DEFAULT NULL COMMENT '标题',
  PRIMARY KEY (`order_shift_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2876 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_partner`
--

DROP TABLE IF EXISTS `tc_partner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_partner` (
  `partner_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `partner_name` varchar(128) DEFAULT NULL COMMENT '伙伴名称',
  `partner_shopex_id` varchar(50) DEFAULT NULL COMMENT '企业ID',
  `partner_department` int(11) DEFAULT NULL COMMENT '所属部门',
  `partner_manager` varchar(128) DEFAULT NULL COMMENT '渠道经理',
  `partner_level` int(11) DEFAULT NULL COMMENT '合作级别',
  `partner_disc` varchar(128) DEFAULT NULL COMMENT '对应折扣',
  `partner_CooperativeTime` datetime DEFAULT NULL COMMENT '合作日期',
  `partner_endingdate` datetime DEFAULT NULL COMMENT '到期时间',
  `partner_contacts` varchar(128) DEFAULT NULL COMMENT '联系人',
  `partner_email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `partner_address` varchar(128) DEFAULT NULL COMMENT '联系地址',
  `partner_fax` int(11) DEFAULT NULL COMMENT '传真',
  `partner_region` varchar(128) DEFAULT NULL COMMENT '所属区域',
  `partner_contract` varchar(128) DEFAULT NULL COMMENT '签约负责人',
  `partner_is_u8` int(11) DEFAULT NULL COMMENT '是否同步U8',
  `partner_pbi_id` varchar(128) DEFAULT NULL COMMENT '老营收ID',
  PRIMARY KEY (`partner_id`)
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_product_basic`
--

DROP TABLE IF EXISTS `tc_product_basic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_product_basic` (
  `product_basic_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `product_basic_code` varchar(50) DEFAULT NULL COMMENT '产品编号',
  `product_basic_name` varchar(128) DEFAULT NULL COMMENT '产品名称',
  `product_basic_category` int(11) DEFAULT NULL COMMENT '一级分类',
  `product_basic_sub_category` int(11) DEFAULT NULL COMMENT '二级分类',
  `product_basic_cost` decimal(20,3) DEFAULT NULL COMMENT '成本价',
  `product_basic_price` decimal(20,3) DEFAULT NULL COMMENT '市场价',
  `product_basic_sales_type` int(11) DEFAULT NULL COMMENT '销售模式',
  `product_basic_charging_type` int(11) DEFAULT NULL COMMENT '收费方式',
  `product_basic_calc_unit` int(11) DEFAULT NULL COMMENT '计量单位',
  `product_basic_service_unit` int(11) DEFAULT NULL COMMENT '服务周期单位',
  `product_basic_can_renewal` int(11) DEFAULT NULL COMMENT '是否可续费',
  `product_basic_has_expired` int(11) DEFAULT NULL COMMENT '是否有有效期',
  `product_basic_need_open` int(11) DEFAULT NULL COMMENT '是否需要开通',
  `product_basic_need_host` int(11) DEFAULT NULL COMMENT '是否需要主机',
  `product_basic_utime` datetime DEFAULT NULL COMMENT '变更信息时间',
  `product_basic_domain_cost` decimal(20,3) DEFAULT NULL COMMENT '域名／备案成本',
  `product_basic_sms_cost` decimal(20,3) DEFAULT NULL COMMENT '短信成本',
  `product_basic_idc_cost` decimal(20,3) DEFAULT NULL COMMENT 'IDC成本',
  `product_basic_confirm_method` varchar(128) DEFAULT NULL COMMENT '确认类型',
  `product_basic_atime` datetime DEFAULT NULL COMMENT '创建时间',
  `product_basic_num` int(11) DEFAULT NULL COMMENT ' 数量',
  PRIMARY KEY (`product_basic_id`)
) ENGINE=InnoDB AUTO_INCREMENT=447 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_product_oparam`
--

DROP TABLE IF EXISTS `tc_product_oparam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_product_oparam` (
  `product_oparam_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `product_oparam_product_basic_id` int(11) DEFAULT NULL COMMENT '基础产品ID',
  `product_oparam_name` varchar(50) DEFAULT NULL COMMENT '参数名称',
  `product_oparam_content` text COMMENT '内容',
  `product_oparam_type` int(11) DEFAULT NULL COMMENT '类型',
  `product_oparam_atime` datetime DEFAULT NULL COMMENT '创建时间',
  `product_oparam_utime` datetime DEFAULT NULL COMMENT '变更信息时间',
  PRIMARY KEY (`product_oparam_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1690 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_product_tech`
--

DROP TABLE IF EXISTS `tc_product_tech`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_product_tech` (
  `product_tech_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `product_tech_code` varchar(128) DEFAULT NULL COMMENT '产品编号',
  `product_tech_name` varchar(128) DEFAULT NULL COMMENT '产品名称',
  `product_tech_desc` varchar(225) DEFAULT NULL COMMENT '产品描述',
  `product_tech_u8_code` varchar(128) DEFAULT NULL COMMENT 'U8编号',
  PRIMARY KEY (`product_tech_id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_ptrelation`
--

DROP TABLE IF EXISTS `tc_ptrelation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_ptrelation` (
  `ptrelation_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `ptrelation_product_tech_id` int(11) NOT NULL COMMENT '技术产品ID',
  `ptrelation_product_basic_id` int(11) NOT NULL COMMENT '基础产品ID',
  PRIMARY KEY (`ptrelation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=458 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_rebate`
--

DROP TABLE IF EXISTS `tc_rebate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_rebate` (
  `rebate_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `rebate_order_id` int(11) DEFAULT NULL COMMENT '所属订单',
  `rebate_create_user` int(11) DEFAULT NULL COMMENT '创建人',
  `rebate_create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `rebate_amount` decimal(20,3) DEFAULT NULL COMMENT '返点金额',
  `rebate_account_id` int(11) DEFAULT NULL COMMENT '选择返点的客户',
  `rebate_pay_method` int(11) DEFAULT NULL COMMENT '付款方式',
  `rebate_pay_info` text COMMENT '付款内容',
  `rebate_status` int(11) DEFAULT NULL COMMENT '返点状态',
  `rebate_note` text COMMENT '返点备注',
  `rebate_person` varchar(225) DEFAULT NULL COMMENT '返点经手人姓名',
  `rebate_examine_note` text COMMENT '审批备注',
  `rebate_book_id` int(11) DEFAULT NULL COMMENT '对应日记账',
  `rebate_pay_account` int(11) DEFAULT NULL COMMENT '支付账户',
  `rebate_reviewer` int(11) DEFAULT NULL COMMENT '业务负责人',
  PRIMARY KEY (`rebate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_refund`
--

DROP TABLE IF EXISTS `tc_refund`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_refund` (
  `refund_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `refund_manage` int(11) DEFAULT NULL COMMENT '业务总监',
  `refund_goods_info` text COMMENT '退款商品信息',
  `refund_pay_method` int(11) DEFAULT NULL COMMENT '退款方式',
  `refund_reason` text COMMENT '退款理由',
  `refund_status` int(11) DEFAULT NULL COMMENT '退款状态',
  `refund_create_user` int(11) DEFAULT NULL COMMENT '创建人',
  `refund_create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `refund_pay_info` text COMMENT '付款信息',
  `refund_order_id` int(11) DEFAULT NULL COMMENT '关联订单',
  `refund_amount` decimal(20,3) DEFAULT NULL COMMENT '退款金额',
  `refund_book_id` int(11) DEFAULT NULL COMMENT '关联日记账',
  `refund_pay_account` int(11) DEFAULT NULL COMMENT '支付账户',
  `refund_number` varchar(128) DEFAULT NULL COMMENT '退款编号',
  `refund_reviewer` int(11) DEFAULT NULL COMMENT '业务负责人',
  PRIMARY KEY (`refund_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_rel_order_order`
--

DROP TABLE IF EXISTS `tc_rel_order_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_rel_order_order` (
  `rel_order_order_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `rel_order_order_rel_order` int(11) DEFAULT NULL COMMENT '关联订单ID',
  `rel_order_order_my_order` int(11) DEFAULT NULL COMMENT '本身订单ID',
  PRIMARY KEY (`rel_order_order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=488 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_role`
--

DROP TABLE IF EXISTS `tc_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `role_name` varchar(128) NOT NULL COMMENT '角色名称',
  `role_desc` varchar(225) NOT NULL COMMENT '角色描述',
  `role_menu_auth` text COMMENT '角色菜单权限',
  `role_activity_auth` text,
  `role_data_auth` text,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_role_user`
--

DROP TABLE IF EXISTS `tc_role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_role_user` (
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '人员id',
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色id',
  PRIMARY KEY (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_salespay`
--

DROP TABLE IF EXISTS `tc_salespay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_salespay` (
  `salespay_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `salespay_order_id` int(11) DEFAULT NULL COMMENT '订单ID',
  `salespay_pay_date` datetime DEFAULT NULL COMMENT '到账/出账日期',
  `salespay_pay_method` int(11) DEFAULT NULL COMMENT '支付方式',
  `salespay_pay_info` varchar(225) DEFAULT NULL COMMENT '支付信息',
  `salespay_status` int(11) DEFAULT NULL COMMENT '到账状态',
  `salespay_pay_note` varchar(225) DEFAULT NULL COMMENT '款项备注',
  `salespay_pay_amount` decimal(20,2) DEFAULT NULL COMMENT '到帐/出账金额',
  `salespay_create_user` int(11) DEFAULT NULL COMMENT '创建人',
  `salespay_create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `salespay_finance` int(11) DEFAULT NULL COMMENT '财务人员',
  `salespay_examine_note` varchar(225) DEFAULT NULL COMMENT '审批备注',
  `salespay_my_bank_account` int(11) DEFAULT NULL COMMENT '商派银行账号',
  `salespay_my_alipay_account` int(11) DEFAULT NULL COMMENT '商派支付宝账号',
  `salespay_book_id` int(11) DEFAULT NULL COMMENT '选择对应日记账',
  `salespay_sp_date` datetime DEFAULT NULL COMMENT '确认到帐日期',
  `salespay_sp_real_date` datetime DEFAULT NULL COMMENT '实际到帐日期（用于U8）',
  `salespay_alipay_order` varchar(128) DEFAULT NULL COMMENT '交易号',
  `salespay_money_type` int(11) DEFAULT NULL COMMENT '款项类型',
  `salespay_number` varchar(128) DEFAULT NULL COMMENT '款项编号',
  `salespay_account_bank_account` varchar(128) DEFAULT NULL COMMENT '客户银行账号',
  `salespay_account_bank_account_name` varchar(128) DEFAULT NULL COMMENT '客户账号姓名',
  `salespay_alipay_account` varchar(128) DEFAULT NULL COMMENT '支付宝账号',
  `salespay_check_name` varchar(128) DEFAULT NULL COMMENT '支票名称',
  `salespay_account_bank_type` int(11) DEFAULT NULL COMMENT '客户账号类型',
  `salespay_account_bank_name` varchar(128) DEFAULT NULL COMMENT '客户开户行名称',
  `salespay_account_alipay_account` varchar(128) DEFAULT NULL COMMENT '客户支付宝账号',
  `salespay_money_type_name` int(11) DEFAULT NULL COMMENT '款项类型名称',
  `salespay_reviewer` int(11) DEFAULT NULL COMMENT '业务负责人',
  `salespay_rebate_account` int(11) DEFAULT NULL COMMENT '返点客户',
  `salespay_rebate_person` varchar(128) DEFAULT NULL COMMENT '返点经手人姓名',
  `salespay_refund_product_txt` text COMMENT '退款产品信息（文本）',
  `salespay_refund_product_json` text COMMENT '退款产品信息（json）',
  `salespay_subsidiary` int(11) DEFAULT NULL COMMENT '所属子公司',
  PRIMARY KEY (`salespay_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8761 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_syn_u8_voucher`
--

DROP TABLE IF EXISTS `tc_syn_u8_voucher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_syn_u8_voucher` (
  `syn_u8_voucher_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `syn_u8_voucher_order` int(11) DEFAULT NULL COMMENT '所属订单ID',
  `syn_u8_voucher_syn_date` datetime DEFAULT NULL COMMENT '同步日期',
  `syn_u8_voucher_query_xml` text COMMENT '提交的xml',
  `syn_u8_voucher_result_xml` text COMMENT '返回的xml',
  `syn_u8_voucher_query_time` datetime DEFAULT NULL COMMENT '提交的时间',
  `syn_u8_voucher_result_time` datetime DEFAULT NULL COMMENT '返回的时间',
  PRIMARY KEY (`syn_u8_voucher_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14853 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_sys_u8`
--

DROP TABLE IF EXISTS `tc_sys_u8`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_sys_u8` (
  `sys_u8_id` int(11) NOT NULL AUTO_INCREMENT,
  `sys_u8_date` date DEFAULT NULL,
  `sys_u8_data` text CHARACTER SET utf8,
  `sys_u8_result` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`sys_u8_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_system_log`
--

DROP TABLE IF EXISTS `tc_system_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_system_log` (
  `system_log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `system_log_user_id` int(11) NOT NULL COMMENT '操作人ID',
  `system_log_ip` varchar(128) NOT NULL COMMENT 'IP地址',
  `system_log_param` varchar(225) NOT NULL COMMENT '详细参数',
  `system_log_addtime` datetime NOT NULL COMMENT '日志添加时间',
  `system_log_login_time` datetime DEFAULT NULL COMMENT '登录时间',
  `system_log_operation` varchar(128) DEFAULT NULL COMMENT '操作',
  `system_log_module` varchar(128) DEFAULT NULL COMMENT '操作模块',
  `system_log_module_id` int(11) DEFAULT NULL COMMENT '模块id',
  `system_log_note` text COMMENT '备注信息',
  PRIMARY KEY (`system_log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57624 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_timing_task`
--

DROP TABLE IF EXISTS `tc_timing_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_timing_task` (
  `timing_task_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `timing_task_do_time` datetime DEFAULT NULL COMMENT '上次执行时间',
  `timing_task_next_time` datetime DEFAULT NULL COMMENT '下次执行时间',
  `timing_task_url` varchar(225) DEFAULT NULL COMMENT '执行路径',
  `timing_task_note` varchar(225) DEFAULT NULL COMMENT '说明',
  `timing_task_status` int(11) DEFAULT NULL COMMENT '状态',
  `timing_token` int(11) DEFAULT NULL COMMENT '线程锁',
  PRIMARY KEY (`timing_task_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_user`
--

DROP TABLE IF EXISTS `tc_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_name` varchar(128) NOT NULL COMMENT '用户名',
  `user_sex` int(11) NOT NULL COMMENT '性别',
  `user_password` char(100) NOT NULL COMMENT '密码',
  `user_email` varchar(50) NOT NULL COMMENT '邮箱',
  `user_department` int(11) NOT NULL COMMENT '所属部门',
  `user_gonghao` varchar(128) NOT NULL COMMENT '工号',
  `user_duty_name` int(11) NOT NULL COMMENT '职位名称',
  `user_status` int(11) NOT NULL COMMENT '状态',
  `user_login_name` varchar(128) NOT NULL COMMENT '登录名',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000132 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tc_user_info`
--

DROP TABLE IF EXISTS `tc_user_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tc_user_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) unsigned NOT NULL,
  `pic_path` char(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-02-02 12:59:14

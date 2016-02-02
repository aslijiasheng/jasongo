<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * Common Functions
 *
 * Loads the base classes and executes the request.
 *
 * @package		CodeIgniter
 * @subpackage	codeigniter
 * @category	Common Functions
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/
 */
// ------------------------------------------------------------------------

/**
 * Determines if the current version of PHP is greater then the supplied value
 *
 * Since there are a few places where we conditionally test for PHP > 5
 * we'll set a static variable.
 *
 * @access	public
 * @param	string
 * @return	bool	TRUE if the current version is $version or higher
 */
if (!function_exists('is_php')) {

    function is_php($version = '5.0.0') {
        static $_is_php;
        $version = (string) $version;

        if (!isset($_is_php[$version])) {
            $_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0) ? FALSE : TRUE;
        }

        return $_is_php[$version];
    }

}

// ------------------------------------------------------------------------

/**
 * Tests for file writability
 *
 * is_writable() returns TRUE on Windows servers when you really can't write to
 * the file, based on the read-only attribute.  is_writable() is also unreliable
 * on Unix servers if safe_mode is on.
 *
 * @access	private
 * @return	void
 */
if (!function_exists('is_really_writable')) {

    function is_really_writable($file) {
        // If we're on a Unix server with safe_mode off we call is_writable
        if (DIRECTORY_SEPARATOR == '/' AND @ ini_get("safe_mode") == FALSE) {
            return is_writable($file);
        }

        // For windows servers and safe_mode "on" installations we'll actually
        // write a file then read it.  Bah...
        if (is_dir($file)) {
            $file = rtrim($file, '/') . '/' . md5(mt_rand(1, 100) . mt_rand(1, 100));

            if (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE) {
                return FALSE;
            }

            fclose($fp);
            @chmod($file, DIR_WRITE_MODE);
            @unlink($file);
            return TRUE;
        } elseif (!is_file($file) OR ( $fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE) {
            return FALSE;
        }

        fclose($fp);
        return TRUE;
    }

}

// ------------------------------------------------------------------------

/**
 * Class registry
 *
 * This function acts as a singleton.  If the requested class does not
 * exist it is instantiated and set to a static variable.  If it has
 * previously been instantiated the variable is returned.
 *
 * @access	public
 * @param	string	the class name being requested
 * @param	string	the directory where the class should be found
 * @param	string	the class name prefix
 * @return	object
 */
if (!function_exists('load_class')) {

    function &load_class($class, $directory = 'libraries', $prefix = 'CI_') {
        static $_classes = array();

        // Does the class exist?  If so, we're done...
        if (isset($_classes[$class])) {
            return $_classes[$class];
        }

        $name = FALSE;

        // Look for the class first in the local application/libraries folder
        // then in the native system/libraries folder
        foreach (array(APPPATH, BASEPATH) as $path) {
            if (file_exists($path . $directory . '/' . $class . '.php')) {
                $name = $prefix . $class;

                if (class_exists($name) === FALSE) {
                    require($path . $directory . '/' . $class . '.php');
                }

                break;
            }
        }

        // Is the request a class extension?  If so we load it too
        if (file_exists(APPPATH . $directory . '/' . config_item('subclass_prefix') . $class . '.php')) {
            $name = config_item('subclass_prefix') . $class;

            if (class_exists($name) === FALSE) {
                require(APPPATH . $directory . '/' . config_item('subclass_prefix') . $class . '.php');
            }
        }

        // Did we find the class?
        if ($name === FALSE) {
            // Note: We use exit() rather then show_error() in order to avoid a
            // self-referencing loop with the Excptions class
            exit('Unable to locate the specified class: ' . $class . '.php');
        }

        // Keep track of what we just loaded
        is_loaded($class);

        $_classes[$class] = new $name();
        return $_classes[$class];
    }

}

// --------------------------------------------------------------------

/**
 * Keeps track of which libraries have been loaded.  This function is
 * called by the load_class() function above
 *
 * @access	public
 * @return	array
 */
if (!function_exists('is_loaded')) {

    function &is_loaded($class = '') {
        static $_is_loaded = array();

        if ($class != '') {
            $_is_loaded[strtolower($class)] = $class;
        }

        return $_is_loaded;
    }

}

// ------------------------------------------------------------------------

/**
 * Loads the main config.php file
 *
 * This function lets us grab the config file even if the Config class
 * hasn't been instantiated yet
 *
 * @access	private
 * @return	array
 */
if (!function_exists('get_config')) {

    function &get_config($replace = array()) {
        static $_config;

        if (isset($_config)) {
            return $_config[0];
        }

        // Is the config file in the environment folder?
        if (!defined('ENVIRONMENT') OR ! file_exists($file_path = APPPATH . 'config/' . ENVIRONMENT . '/config.php')) {
            $file_path = APPPATH . 'config/config.php';
        }

        // Fetch the config file
        if (!file_exists($file_path)) {
            exit('The configuration file does not exist.');
        }

        require($file_path);

        // Does the $config array exist in the file?
        if (!isset($config) OR ! is_array($config)) {
            exit('Your config file does not appear to be formatted correctly.');
        }

        // Are any values being dynamically replaced?
        if (count($replace) > 0) {
            foreach ($replace as $key => $val) {
                if (isset($config[$key])) {
                    $config[$key] = $val;
                }
            }
        }

        return $_config[0] = & $config;
    }

}

// ------------------------------------------------------------------------

/**
 * Returns the specified config item
 *
 * @access	public
 * @return	mixed
 */
if (!function_exists('config_item')) {

    function config_item($item) {
        static $_config_item = array();

        if (!isset($_config_item[$item])) {
            $config = & get_config();

            if (!isset($config[$item])) {
                return FALSE;
            }
            $_config_item[$item] = $config[$item];
        }

        return $_config_item[$item];
    }

}

// ------------------------------------------------------------------------

/**
 * Error Handler
 *
 * This function lets us invoke the exception class and
 * display errors using the standard error template located
 * in application/errors/errors.php
 * This function will send the error page directly to the
 * browser and exit.
 *
 * @access	public
 * @return	void
 */
if (!function_exists('show_error')) {

    function show_error($message, $status_code = 500, $heading = 'An Error Was Encountered') {
        $_error = & load_class('Exceptions', 'core');
        echo $_error->show_error($heading, $message, 'error_general', $status_code);
        exit;
    }

}

// ------------------------------------------------------------------------

/**
 * 404 Page Handler
 *
 * This function is similar to the show_error() function above
 * However, instead of the standard error template it displays
 * 404 errors.
 *
 * @access	public
 * @return	void
 */
if (!function_exists('show_404')) {

    function show_404($page = '', $log_error = TRUE) {
        $_error = & load_class('Exceptions', 'core');
        $_error->show_404($page, $log_error);
        exit;
    }

}

// ------------------------------------------------------------------------

/**
 * Error Logging Interface
 *
 * We use this as a simple mechanism to access the logging
 * class and send messages to be logged.
 *
 * @access	public
 * @return	void
 */
if (!function_exists('log_message')) {

    function log_message($level = 'error', $message, $php_error = FALSE) {
        static $_log;

        if (config_item('log_threshold') == 0) {
            return;
        }

        $_log = & load_class('Log');
        $_log->write_log($level, $message, $php_error);
    }

}

// ------------------------------------------------------------------------

/**
 * Set HTTP Status Header
 *
 * @access	public
 * @param	int		the status code
 * @param	string
 * @return	void
 */
if (!function_exists('set_status_header')) {

    function set_status_header($code = 200, $text = '') {
        $stati = array(
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );

        if ($code == '' OR ! is_numeric($code)) {
            show_error('Status codes must be numeric', 500);
        }

        if (isset($stati[$code]) AND $text == '') {
            $text = $stati[$code];
        }

        if ($text == '') {
            show_error('No status text available.  Please check your status code number or supply your own message text.', 500);
        }

        $server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;

        if (substr(php_sapi_name(), 0, 3) == 'cgi') {
            header("Status: {$code} {$text}", TRUE);
        } elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0') {
            header($server_protocol . " {$code} {$text}", TRUE, $code);
        } else {
            header("HTTP/1.1 {$code} {$text}", TRUE, $code);
        }
    }

}

// --------------------------------------------------------------------

/**
 * Exception Handler
 *
 * This is the custom exception handler that is declaired at the top
 * of Codeigniter.php.  The main reason we use this is to permit
 * PHP errors to be logged in our own log files since the user may
 * not have access to server logs. Since this function
 * effectively intercepts PHP errors, however, we also need
 * to display errors based on the current error_reporting level.
 * We do that with the use of a PHP error template.
 *
 * @access	private
 * @return	void
 */
if (!function_exists('_exception_handler')) {

    function _exception_handler($severity, $message, $filepath, $line) {
        // We don't bother with "strict" notices since they tend to fill up
        // the log file with excess information that isn't normally very helpful.
        // For example, if you are running PHP 5 and you use version 4 style
        // class functions (without prefixes like "public", "private", etc.)
        // you'll get notices telling you that these have been deprecated.
        if ($severity == E_STRICT) {
            return;
        }

        $_error = & load_class('Exceptions', 'core');

        // Should we display the error? We'll get the current error_reporting
        // level and add its bits with the severity bits to find out.
        if (($severity & error_reporting()) == $severity) {
            $_error->show_php_error($severity, $message, $filepath, $line);
        }

        // Should we log the error?  No?  We're done...
        if (config_item('log_threshold') == 0) {
            return;
        }

        $_error->log_exception($severity, $message, $filepath, $line);
    }

}

// --------------------------------------------------------------------

/**
 * Remove Invisible Characters
 *
 * This prevents sandwiching null characters
 * between ascii characters, like Java\0script.
 *
 * @access	public
 * @param	string
 * @return	string
 */
if (!function_exists('remove_invisible_characters')) {

    function remove_invisible_characters($str, $url_encoded = TRUE) {
        $non_displayables = array();

        // every control character except newline (dec 10)
        // carriage return (dec 13), and horizontal tab (dec 09)

        if ($url_encoded) {
            $non_displayables[] = '/%0[0-8bcef]/'; // url encoded 00-08, 11, 12, 14, 15
            $non_displayables[] = '/%1[0-9a-f]/'; // url encoded 16-31
        }

        $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S'; // 00-08, 11, 12, 14-31, 127

        do {
            $str = preg_replace($non_displayables, '', $str, -1, $count);
        } while ($count);

        return $str;
    }

}

// ------------------------------------------------------------------------

/**
 * Returns HTML escaped variable
 *
 * @access	public
 * @param	mixed
 * @return	mixed
 */
if (!function_exists('html_escape')) {

    function html_escape($var) {
        if (is_array($var)) {
            return array_map('html_escape', $var);
        } else {
            return htmlspecialchars($var, ENT_QUOTES, config_item('charset'));
        }
    }

}

/* End of file Common.php */
/* Location: ./system/core/Common.php */

function p($arr) {

    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}

function success($url, $msg) {

    header('Content-Type:text/html;charset=utf-8');
    $url = site_url($url);
    echo "<script>alert('$msg');location.href='$url'</script>";
    die;
}

function lee_jump($url, $msg = "") {
    header('Content-Type:text/html;charset=utf-8');
    if ($msg == "") {
        echo "<script>location.href='" . $url . "'</script>";
    } else {
        echo "<script>alert('$msg');location.href='" . $url . "'</script>";
    }
    die;
}

function error($msg) {
    header('Content-Type:text/html;charset=utf-8');
    echo "<script>alert('$msg');window.history.back();</script>";
    die;
}

/* 自定义表单提交数据处理函数 */

function attribute($data) {
    foreach ($data as $k => $v) {
        if ($k != 'submit') {
            $arr[$k] = $v;
        }
        $arr['update_time'] = time();
    }
    return $arr;
}

function replace($row, $name, $content) {
    $row = str_replace("%name%", $name, $row);
    $row = str_replace("%content%", $content, $row);
    return $row;
}

function replace_view($row, $content) {
    $row = str_replace("%form_content%", $content, $row);
    return $row;
}

function code($width = 80, $height = 25, $fontSize = 15, $codeLen = 6) {

    $config = array(
        'width' => $width,
        'height' => $height,
        'fontSize' => $fontSize,
        'codeLen' => $codeLen,
    );
    $this->load->library('code', $config);
    // p($this->code->getCode());
    if (!isset($_SESSION)) {
        session_start();
    }
    $_SESSION['code'] = $this->code->getCode();
    echo $this->code->show();
}

/* 属性字段类型映射函数 */

function field_type($v) {
    switch ($v) {
        case 1:
            return "int";
            break;
        case 2:
            return "varchar(30)";
            break;
        case 3:
            return "char(30)";
            break;
        case 4:
            return "date";
            break;
        case 5:
            return "int";
            break;
        case 6:
        case 7:
        case 8:
            return "char(20)";
            break;
    }
}

function pj($arr) {
    echo $json = json_encode($arr);
}

function bingji($arr1, $arr2) {
    $ilength = count($arr1);
    $jlength = count($arr2);
    for ($i = 0; $i < $jlength; $i++) {
        $change = false;
        for ($j = 0; $j < $ilength; $j++) {
            if ($arr2[$i] == $arr1[$j]) {
                $change = true;
                break;
            }
        }
        if ($change == false) {
            $arr1[] = $arr2[$i];
        }
    }
    return $arr1;
}

// function my_bingji($arr1,$arr2){
//    foreach($arr1 as $key => $value){
//    }
// }
//数值转金额
function money($num) {
    //money_format(format, number)
    //number_format(number)
    return number_format(floatval($num), 2, '.', '');
}

//stdClass Object转array 

function object_array($array) {
    if (is_object($array)) {
        $array = get_object_vars($array);
    }
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            $array[$key] = object_array($value);
        }
    }
    return $array;
}

//数据权限值转数字
function data_auth_to_num($data_auth_val) {
    if ($data_auth_val == 'all_privileges') {
        return 4;
    } else if ($data_auth_val == 'charge') {
        return 1;
    } else if ($data_auth_val == 'self_department') {
        return 2;
    } else {
        return 3;
    }
}

//转化数组
function ConverArray($arr, $split = ",") {
    return explode($split, $arr);
}

/**
 * 将数据转化成字符串
 * @params array $glue 连接符
 * @params array $pieces 要转化的数组
 * @return string 返回转化后的字符串
 */
function implode_r($glue, $pieces) {
    $return = "";

    if (!is_array($glue)) {
        $glue = array($glue);
    }

    $thisLevelGlue = array_shift($glue);

    if (!count($glue))
        $glue = array($thisLevelGlue);

    if (!is_array($pieces)) {
        return (string) $pieces;
    }

    foreach ($pieces as $sub) {
        $return .= implode_r($glue, $sub) . $thisLevelGlue;
    }

    if (count($pieces))
        $return = substr($return, 0, strlen($return) - strlen($thisLevelGlue));

    return $return;
}

/**
 * 输出返回的JSON结果状态值
 * @params string $res 返回状态值的布尔值
 * @params string $msg 返回状态值的信息
 * @return json 返回的JSON数据
 */
function _msg($res, $msg) {
    return json_encode(
            array(
                "res" => $res, "msg" => $msg
            )
    );
}

/**
 * 数字索引数组转化成KEY VALUE
 * @param array $HashData    待转化的值
 * @param string $HashKey     索引键值对应的值
 * @return array 返回的数组数据
 */
function _HashData($HashData, $HashKey) {
    $ResData = array();
    foreach ($HashData as $key => $value) {
        foreach ($value as $k => $v) {
            $ResData[$value[$HashKey]][$k] = $v;
        }
    }
    return $ResData;
}

/**
 * 二维数组转化成一维数组 有待完善成多维数组转化
 * @param type $arrs 二维数组
 * @return type  返回一维数组
 */
function _ConverOneArray($arrs) {
    $res_data = array();
    foreach ($arrs as $k => $v) {
        foreach ($v as $kk => $vv) {
            if (!empty($vv)) {
                $res_data[] = $vv;
            }
        }
    }
    return $res_data;
}

/**
 * 时间转换函数加8小时
 * @param string  $format    时间格式化的字符串
 * @return string    序列号好的时间
 */
function dataAdd8($format) {
    return date($format, time() + 3600 * 8);
}

/**
 * 时间转换函数减少8小时
 * @param string  $format    时间格式化的字符串
 * @param string  $format    需要转换的时间
 * @return string    序列号好的时间
 */
function dataReduce8($format, $time) {
    return date($format, strtotime($time) - 3600 * 8);
    ;
}

/**
 * 多维数组去重
 */
function super_unique($array) {
    $result = array_map("unserialize", array_unique(array_map("serialize", $array)));
    foreach ($result as $key => $value) {
        if (is_array($value)) {
            $result[$key] = super_unique($value);
        }
    }
    return $result;
}

/**
 * date_add_sub()
 * 日期加减运算
 * @param mixed $sStartDate
 * @param string $formatter
 * @param string $add_sub
 * @return
 */
function date_add_sub($sStartDate, $add_sub = '+2 days', $formatter = 'Y-m-d') {
    return date($formatter, strtotime($add_sub, strtotime($sStartDate)));
}

/**
 * date_time()
 * 日期加减时间间隔
 * @param mixed $DateTime
 * @param integer $experis
 * @return
 */
function date_time($DateTime, $experis = 28800) {
    $res_date = empty($DateTime) ? null : date("Y-m-d H:i:s", strtotime($DateTime) + $experis);
    return $res_date;
}

/** mysql防sql注入
 * @param $str
 * @return string
 */
function mysqlEscapeStr($str){
    return addslashes($str);
}

/**
 * modelRequired 
 * 验证数据表规则返回错误信息与字段
 * @param mixed $modelRequired 
 * @param mixed $fieldsName 
 * @param mixed $modelData 
 * @access public
 * @return void
 */
function modelRequired($modelRequired, $fieldsName, $modelData){
    $errorRet = array('succ' => TRUE, 'msg' => array());
    $requiredAttr = array();
    $errorMsg = array();
    if($modelRequired){
        //根据传入的属性规则先得出hash数据
        foreach($fieldsName as $fields){
            list($ruleField, $attrField) = explode("|", $fields);
            $requiredAttr[$attrField] = $ruleField;
        }
        //匹配数据列表是否与规则满足
        foreach($modelData as $modelKey => $modelVal){
            /**
             *  判断键值是否在规则内部如果在进行判断 如果不在则记录
             */
            if(isset($requiredAttr[$modelKey])){
                $rule = $requiredAttr[$modelKey];
                $rule = trim($rule, '`');
                list($sign, $type, $length, $isPrimary, $relation) = explode("#", $rule);
                $ruleRet = ruleFunc($sign, $type, $length, $isPrimary, $relation, $modelVal);
                if(!$ruleRet){
                    $errorRet['msg'][] = $ruleRet;
                    $errorRet['succ'] = FALSE;
                }
            }else{
                $errorRet['msg']['noRule'][] = 'NOTICE:key {$k} is not include rule';
                $errorRet['succ'] = FALSE;
            }
        }
    }
    return $errorRet;
}

/**
 * ruleFunc 
 * 规则认证
 * @param mixed $sign 
 * @param mixed $type 
 * @param mixed $length 
 * @param mixed $isPrimary 
 * @param mixed $relation 
 * @param mixed $val 
 * @access public
 * @return void
 */
function ruleFunc($sign, $type, $length, $isPrimary, $relation, $val){
    $val = trim($val);
    $lenVal = mb_strlen($val, 'UTF-8');
    $typeVal = gettype($val);
    if($sign === "ignore"){
        return true;
    }elseif($sign === "required"){
        list($typeRuleK, $typeRuleV) = explode(":", $type);
        list($lengthRuleK, $lengthRuleV) = explode(":", $length);
        if(empty($val)){
            return "{$val}---不能为空";
        }
        if($typeVal !== $typeRuleV){
            return "{$val}---类型不匹配";
        }
        if($lenVal > $lengthRuleV){
            return "{$val}---长度不匹配";
        }
    }else{
        return "{$sign}未知信号";
    }
    return TRUE;
}

/**
 * regexPhone 
 * 验证手机号码
 * @param mixed $phoneNumber 
 * @access public
 * @return void
 */
function regexPhone($phoneNumber){
    if(preg_match("/1[3458]{1}\d{9}$/", $phoneNumber)){
        return TRUE;
    }else{
        return FALSE;
    }
}

/**
 * regexTel 
 * 验证固定电话号码
 * @param mixed $telNumber 
 * @access public
 * @return void
 */
function regexTel($telNumber){
    if(preg_match("/^([0-9]{3,4}-)?[0-9]{7,8}$/", $telNumber)){
        return TRUE;
    }else{
        return FALSE;
    }
}

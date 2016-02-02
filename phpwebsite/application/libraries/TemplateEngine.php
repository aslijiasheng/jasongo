<?php
class TemplateEngine{
	//模板和参数的替换规则
	public function replace_rule($row,$data){
		//去掉模板的注释
		$row = $this->replace_notes($row,$data);
		//替换
		$row = $this->replace_variables($row,$data);
		//循环
		$row = $this->replace_foreach($row,$data);
		//循环套循环方法
		$row = $this->replace_foreachs($row,$data);
		//调用模板
		$row = $this->replace_include($row,$data);
		//判断
		$row = $this->replace_if($row,$data);
		//最后再替换一次保证全部替换了
		$row = $this->replace_variables($row,$data);
		return $row;
	}

	//内容与数组数据替换的方法
	public function replace_variables($row,$data){
		//将所有{#...#}中间的内容全部替换成对应的数据
		$zz = "/{#([^#]*)#}/iUs";
		preg_match_all($zz,$row,$arr);
		//p($arr);//exit;
		//循环所有的值！然后替换掉
		foreach ($arr[1] as $key=>$value){
			if (isset($data[$value])){
				$row = str_replace($arr[0][$key],$data[$value],$row);
			}else{
				//echo $value;exit;
				$result = $this->replace_arr($value,$data);
				$row = str_replace($arr[0][$key],$result,$row);
			}
		}
		return $row;
	}
	
	//匹配数组 （单个内容匹配）
	public function replace_arr($row,$data){
		$zz2 = "/{#[^#\s\/\|]*#}/iUs";
		preg_match_all($zz2,"{#".$row."#}",$arr2);
		if(isset($arr2[0][0])){
			$row_arr = explode(".",$row);//用点分割用来处理数组
			if(count($row_arr)>1){
				switch (count($row_arr)){ 
					case 2:
						if(isset($data[$row_arr[0]][$row_arr[1]])){
							$row = str_replace($row,$data[$row_arr[0]][$row_arr[1]],$row);
						}
						break;
					case 3:
						if(isset($data[$row_arr[0]][$row_arr[1]][$row_arr[2]])){
							$row = str_replace($row,$data[$row_arr[0]][$row_arr[1]][$row_arr[2]],$row);
						}
						break;
					case 4:
						if(isset($data[$row_arr[0]][$row_arr[1]][$row_arr[2]][$row_arr[3]])){
							$row = str_replace($row,$data[$row_arr[0]][$row_arr[1]][$row_arr[2]][$row_arr[3]],$row);
						}
						break;
					default:
						echo count($value_arr)."数组层次过高！没写！";exit;
				}
			}else{
				$row = "{#".$row."#}";
			}
			return $row;
		}else{
			//p("no");
			return "{#".$row."#}";
		}

	}

	//调用模板的方法
	public function replace_include($row,$data){
		$zz="/{#include ([^#]*)#}/iUs";
		preg_match_all($zz,$row,$arr);
		foreach ($arr[1] as $k=>$v){
			$all = $arr[0][$k];//全部内容
			$url = $arr[1][$k];//模板地址
			$template_url='application/template/'.$url; //模板所在地址
			//读取模板信息
			//p($template_url);
			$nr = file_get_contents($template_url);
			//模板内容部分再处理1次替换
			$nr = $this->replace_variables($nr,$data);

			$row = str_replace($all,$nr,$row);
		}
		//p($arr);exit;
		return $row;
	}
	
	//专门给循环的数组写的方法
	public function get_from_arr($row,$data){
		$row_arr = explode(".",$row);//用点分割用来处理数组
		$row_data=$data;
		foreach ($row_arr as $k=>$v){
			if (isset($row_data[$v])){
				$row_data=$row_data[$v];
			}else{
				$row_data="";
			}
		}
		return $row_data; //这里最后给的一般是数组
	}

	

	/*
	public function get_from_arr($row,$data){
		$row_arr = explode(".",$row);//用点分割用来处理数组
		
		//p($row_arr);
		//p($row);
		switch (count($row_arr)){
			case 1: //等于1表示不是数组，直接给他需要的值
				//p($row);
				$row_data = $data[$row_arr[0]];
				//p($data);
				break;
			case 2:
				$row_data = $data[$row_arr[0]][$row_arr[1]];
				break;
			case 3:
				$row_data = $data[$row_arr[0]][$row_arr[1]][$row_arr[2]];
				break;
			case 4:
				$row_data = $data[$row_arr[0]][$row_arr[1]][$row_arr[2]][$row_arr[3]];
				break;
			default:
				echo count($value_arr)."数组层次过高！没写！";exit;
		}
		return $row_data; //这里最后给的一般是数组
	}
	*/
	
	//这里来实现循环的替换方法
	public function replace_foreach($row,$data){
		//echo $row;
		$zz = "/{#foreach ([^#]*)#}(.+){#\/foreach#}/iUs";
		preg_match_all($zz,$row,$arr);
		//p($arr);
		//循环所有的值！然后替换掉
		foreach ($arr[1] as $key=>$value){
			//p($value);exit;
			$p = explode(" ",$value);
			//然后再循环将参数用等号转换数组
			foreach ($p as $p_k=>$p_v){
				$p_k_v = explode("=",$p_v);
				//p($p_k_v);
				$parameters[$p_k_v[0]] = $p_k_v[1];
			}
			//sp($parameters);
			$from = $parameters['from']; //参数1 //需要读取的数组
			//exit;
			$fromdata = $this->get_from_arr($from,$data);//这里的from参数获取对应的数组内容

			$item = $parameters['item']; //参数2
			$p_key = $parameters['key']; //参数3 //循环的序号 暂时没用
			$content = $arr[2][$key];//内容
			$content_all=""; //全部内容
			//循环出这个变量的所有值然后组合成内容
			//p($item);exit;
			foreach ($fromdata as $f_k=>$f_v){
				//p($fromdata);exit;
				$cc = $this->replace_foreach_th($content,$fromdata[$f_k],$item,$data);
				//echo $cc;exit;
				//替换key
				$cc = str_replace("{#".$p_key."#}",$f_k,$cc);
				$content_all .= $cc;
			}
			//最后替换整个循环
			$row = str_replace($arr[0][$key],$content_all,$row);
			
		}
		return $row;
	}

	//循环里的替换方法（单独拿出来处理方便递归调用）
	public function replace_foreach_th($row,$fromdata,$item,$data){
		//找到内容的所有需要替换的值！替换
		$content_zz = "/{#".$item."\|([^#]*)#}/iUs";
		preg_match_all($content_zz,$row,$lee_arr);
		//p($lee_arr);exit;
		//循环所有的值！然后替换掉
		foreach ($lee_arr[1] as $la_k=>$la_v){
			if (isset($fromdata[$la_v])){
				$row = str_replace($lee_arr[0][$la_k],$fromdata[$la_v],$row);
			}
			else{
				//数组替换
				$result = $this->replace_arr($la_v,$fromdata);
				//p($la_v);
				//$result = $this->get_from_arr($la_v,$fromdata);
				$row = str_replace($lee_arr[0][$la_k],$result,$row);
			}
		}

		//在这里判断一下是否有调用，如果有调用！则不去掉最开始的条件
		$include_zz="/{#include ([^#]*)#}/iUs";
		preg_match_all($include_zz,$row,$include_arr);
		if(isset($include_arr[0][0])){
			//p($include_arr[0][0]);
			$row = $this->replace_include($row,$data);
			//加上循环的条件
			$row = $this->replace_foreach_th($row,$fromdata,$item,$data);
		}		
		return $row;
	}

	//这里实现判断的正则替换
	public function replace_if($row,$data){
		//首选获取所有的if
		$zz = "/({#if ([^#]*)#})(.+)({#\/if#})/iUs"; 
		preg_match_all($zz,$row,$arr);
		//p($arr);
		//循环处理每个if然后
		foreach ($arr[0] as $k=>$v){
			//首先要判断里面是否有else方法
			$all = $arr[0][$k];//全部内容
			$start = $arr[1][$k];//开始的if
			$tj = $arr[2][$k];//判断条件
			$nr = $arr[3][$k];//中间内容
			$end = $arr[4][$k];//结尾的/if
			
			//直接用{#else#}分割出来
			$is_else = strstr($nr,"{#else#}");
			if($is_else){
				//有else
				$lr_nr = explode("{#else#}",$nr);
				$lr_val = explode("==",$tj);//分割成左右2个值来判断
				if ($lr_val[0]==$lr_val[1]){
					$v = str_replace($start,"",$v);
					$v = str_replace($end,"",$v);
					$v = str_replace($lr_nr[1],"",$v);
					$v = str_replace('{#else#}',"",$v);
				}else{
					$v = str_replace($start,"",$v);
					$v = str_replace($end,"",$v);
					$v = str_replace($lr_nr[0],"",$v);
					$v = str_replace('{#else#}',"",$v);
				}
			}else{
				//没有else
				$lr_val = explode("==",$tj);//分割成左右2个值
				if ($lr_val[0]==$lr_val[1]){
					$v = str_replace($start,"",$v);
					$v = str_replace($end,"",$v);
				}else{
					$v = str_replace($all,"",$v);
				}
			}
			//echo $v;
			//最后将全部内容替换掉
			$row = str_replace($all,$v,$row);
		}
		//exit;
		//p($arr);exit;
		return $row;
	}

	//模板引擎专用注释 //*   *//
	public function replace_notes($row,$data){
		$zz = "/\/\/\*(.+)\*\/\//iUs"; 
		preg_match_all($zz,$row,$arr);
		foreach ($arr[1] as $key=>$value){
			$row = str_replace($arr[0][$key],"",$row);
		}
		return $row;
	}

	//模板测试
	public function lee_cs($row){
		echo $this->load->view('admin/layouts/head','',true);
		echo $row;
		echo $this->load->view('admin/layouts/bottom','',true);
	}


	//循环套循环的方法
	public function replace_foreachs($row,$data,$i=0){
		$i++;
		//echo $row;exit;
		$zz = "/{#foreach".$i." ([^#]*)#}(.+){#\/foreach".$i."#}/iUs";
		preg_match_all($zz,$row,$arr);
		//p($arr);
		//循环所有的值！然后替换掉
		$fromdata="";
		foreach ($arr[1] as $key=>$value){
			//p($value);exit;
			$p = explode(" ",$value);
			//然后再循环将参数用等号转换数组
			foreach ($p as $p_k=>$p_v){
				$p_k_v = explode("=",$p_v);
				//p($p_k_v);
				$parameters[$p_k_v[0]] = $p_k_v[1];
			}
			//sp($parameters);
			$from = $parameters['from']; //参数1 //需要读取的数组
			//判断下这个数组是否存在
			$fromdata = $this->get_from_arr($from,$data);//这里的from参数获取对应的数组内容
			$item = $parameters['item']; //参数2
			$p_key = $parameters['key']; //参数3 //循环的序号 暂时没用
			$content = $arr[2][$key];//内容
			$content_all=""; //全部内容
			if($fromdata!=""){
				
				//循环出这个变量的所有值然后组合成内容
				foreach ($fromdata as $f_k=>$f_v){
					//p($fromdata);exit;
					$cc = $this->replace_foreach_th($content,$fromdata[$f_k],$item,$data);
					//echo $cc;exit;
					//替换key
					$cc = str_replace("{#".$p_key."#}",$f_k,$cc);
					//替换本身的值
					if(!is_array($f_v)){
						$cc = str_replace("{#".$item."#}",$f_v,$cc);
					}
					if($i<=3){
						$data=$f_v;
						$cc = $this->replace_foreachs($cc,$data,$i);
					}
					$content_all .= $cc;
				}
				//最后替换整个循环
				$row = str_replace($arr[0][$key],$content_all,$row);
			}else{
				//echo $arr[0][$key];
				//echo "<br><br>";
				$row = str_replace($arr[0][$key],"",$row);
			}
			
		}
		return $row;
	}
}
?>
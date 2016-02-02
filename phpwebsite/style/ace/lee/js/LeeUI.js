function LeeUI(url){
	
	//编辑、查看页面布局控件
	document.write("<script src='"+url+"style/ace/lee/js/Format.js'></script>");
	//form各种类型控件生成
	document.write("<script src='"+url+"style/ace/lee/js/Form.js'></script>");
	//高级查询
	document.write("<script src='"+url+"style/ace/lee/js/SeniorQuery.js'></script>");
	//时间控件
	//document.write("<link rel='stylesheet' href='"+url+"style/ace/css/datepicker.css' />");
	//document.write("<script src='"+url+"style/ace/js/jquery.validate.min.js'></script>");
	//时间控件
	document.write("<link href='"+url+"style/ace/lee/datetimepicker/css/bootstrap-datetimepicker.min.css' rel='stylesheet'>");
	document.write("<script src='"+url+"style/ace/lee/datetimepicker/js/bootstrap-datetimepicker.js'></script>");
	document.write("<script src='"+url+"style/ace/lee/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js'></script>");

	//表单验证的控件
	document.write("<script src='"+url+"style/ace/js/jquery.validate.min.js'></script>");
	//表单验证的中文定义
	document.write("<script src='"+url+"style/ace/lee/js/validate_cn.js'></script>");
	//表单验证的验证规则定义（正则）
	document.write("<script src='"+url+"style/ace/lee/js/validate_add_method.js'></script>");


	//自定义ajax加载的dialog
	document.write("<script src='"+url+"style/ace/lee/js/DivDialog.js'></script>");

	//bootbox控件 一般处理alert()的样式
	document.write("<script src='"+url+"style/ace/js/bootbox.min.js'></script>");

	document.write("<script src='"+url+"style/ace/js/fuelux/fuelux.wizard.min.js'></script>");
	document.write("<script src='"+url+"style/ace/js/ace-elements.min.js'></script>");
	document.write("<script src='"+url+"style/ace/lee/js/ajaxfileupload.js'></script>");

	//table列表控件
	document.write("<script src='"+url+"style/ace/lee/js/LeeTable.js'></script>");

	//图表控件加载（饼图、漏斗等。。。）
	document.write("<script src='"+url+"style/ace/lee/js/build/dist/echarts.js'></script>");

    //通用控件
    document.write("<script src='"+url+"style/ace/lee/js/Lee.js'></script>");

	//专门给form表单加载计算型字段的控件
	document.write("<script src='"+url+"style/ace/lee/js/FormCalcAttr.js'></script>");

}

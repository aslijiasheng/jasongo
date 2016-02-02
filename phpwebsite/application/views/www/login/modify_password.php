<script type="text/javascript">
    $(document).ready(function () {
        $('[name=mcancel]').click(function(){
        	$('#modify').dialog("close");
        	});
        return false;
    });

</script>
<div class = "col-xs-12">
	<form id="Form11" onsubmit="return false">
	<table class = "table table-striped table-bordered">
		<tbody>
			<tr>
				<td rowspan="1" colspan="1" style="width:25%;text-align:right;font-weight:bold;color:#0daed3;line-height: 34px;">原密码</td>
				<td rowspan="1" colspan="1" style="width:75%;text-align:left;">
					<div class="form-group">
						<input type="password" class="col-xs-12" name='User.LoginPassword'>
					</div>
				</td>
			</tr>
			<tr>
				<td rowspan="1" colspan="1" style="width:25%;text-align:right;font-weight:bold;color:#0daed3;line-height: 34px;">密码</td>
				<td rowspan="1" colspan="1" style="width:75%;text-align:left;">
					<div class="form-group">
						<input id="password" type="password" class="col-xs-12" name='password'>
					</div>
				</td>
			</tr>
			<tr>
				<td rowspan="1" colspan="1" style="width:25%;text-align:right;font-weight:bold;color:#0daed3;line-height: 34px;">确认密码</td>
				<td rowspan="1" colspan="1" style="width:75%;text-align:left;">
					<div class="form-group">
						<input id="confirm_password" type="password" class="col-xs-12" name='confirm_password'>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="col-xs-12">		
		<div class="hr hr-dotted hr-16"></div>
		<button class="btn btn-info" name="msave">
			<i class="ace-icon fa fa-pencil align-top bigger-125"></i>
			保存
		</button>
		<a class="btn btn-danger" name="mcancel">
			<i class="ace-icon fa fa-pencil align-top bigger-125"></i>
			取消
		</a>

	</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	//添加验证控件
	$('#Form11').validate({
        rules:{
            password: {
                required: true,
                minlength: 5
            },
            confirm_password: {
                required: true,
                minlength: 5,
                equalTo: "#password"
            }
        },
        submitHandler: function() { 
	    	var data = $("#Form11").serializeArray();
	        $.ajax({
	            'type':'post',
	            'data':data,
	            'success':function(data){

	            	data = eval("("+data+")");
	            	
	                if(data.res=='suc'){
	                	alert(data.msg);
	                	$("#modify").dialog("close");
	                }else{
	                	alert(data.msg);
	                }
	                
	            },
	            'url':"<?php echo base_url();?>index.php/www/user/update_password",
	            'cache':false
	        });
	    }
	});
});

// $.validator.setDefaults({  
// 	    submitHandler: function() { 
// 	    	var data = $("#Form11").serializeArray();
// 	        $.ajax({
// 	            'type':'post',
// 	            'data':data,
// 	            'success':function(data){

// 	            	data = eval("("+data+")");
	            	
// 	                if(data.res=='suc'){
// 	                	alert(data.msg);
// 	                	$("#modify").dialog("close");
// 	                }else{
// 	                	alert(data.msg);
// 	                }
	                
// 	            },
// 	            'url':"<?php echo base_url();?>index.php/www/user/update_password",
// 	            'cache':false
// 	        });
// 	    }  
// 	});
</script>
</div>
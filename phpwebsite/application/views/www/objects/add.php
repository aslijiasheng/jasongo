<script type="text/javascript">
    $(document).ready(function () {
        $('[name=cancel]').click(function(){
        	location.href="<?php echo base_url();?>index.php/www/objects/lists?obj_type=<?php echo $obj_type;?>"
        	});
        return false;
    });

</script>
			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<!-- #section:basics/content.breadcrumbs -->
				<div class="breadcrumbs" id="breadcrumbs">
					<ul class="breadcrumb">
						<li>
							<i class="ace-icon fa fa-home home-icon"></i>
							<a href="<?php echo base_url();?>index.php/www">首页</a>
						</li>
						<li class="active"><a href="<?php echo base_url();?>index.php/www/objects/lists?obj_type=<?php echo $obj_type;?>>"><?php echo $dict;?>列表</a></li>
						<li>新增<?php echo $dict;?></li>
					</ul><!-- /.breadcrumb -->
					<!-- /section:basics/content.searchbox -->
				</div>

				<!-- /section:basics/content.breadcrumbs -->
				<div class="page-content">
					<!-- /section:settings.box -->
					<div class="page-header">
						<h1>
							新增<?php echo $dict;?>
							<small>
								<?php echo $module;?> Add
							</small>
						</h1>
					</div><!-- /.page-header -->
					
					<!--lee 这里是中间内容部分 start-->
					<div class="row">
						<form id="Form" onsubmit="return false">
							<input type="hidden" name="<?php echo $primary['key'];?>" value="<?php echo $primary['value'];?>"/>
							<input type="hidden" name = "module_model" value="<?php echo $module;?>"/>
							<input type="hidden" name = "module_action" value="module_add"/>
							<div class="col-xs-12">
								<button class="btn btn-info" name="save">
									<i class="ace-icon fa fa-pencil align-top bigger-125"></i>
									保存
								</button>
								<button class="btn btn-danger" name="cancel">
									<i class="ace-icon fa fa-pencil align-top bigger-125"></i>
									取消
								</button>
								<div class="hr hr-dotted hr-16"></div>
							</div>
						
							<div class="col-xs-12" id="EditFormat"></div>
                            <div class="hr hr-dotted hr-16"></div>
                            <div id="detail" class="col-xs-12"></div>
							<div class="col-xs-12">
								<div class="hr hr-dotted hr-16"></div>
								<button class="btn btn-info" name="save">
									<i class="ace-icon fa fa-pencil align-top bigger-125"></i>
									保存
								</button>
								
								<button class="btn btn-danger" name="cancel">
									<i class="ace-icon fa fa-pencil align-top bigger-125"></i>
									取消
								</button>
								
							</div>
						</form>
						<script type="text/javascript">
							//编辑页面布局的table生成
							$(document).ready(function() {
								var format_data = <?php echo json_encode($format_data);?>;
								$("#EditFormat").Format({
									type: "edit",
									FormatData: format_data,
									FromID:"Form",
									SelectChildEnumsUrl:'<?php echo base_url();?>index.php/admin/enum/SelectChildEnums'
								});

                                var dtl_obj_name = "<?php echo $dtl_obj_name?>";

                                if(dtl_obj_name.length != 0){
                                    $("#detail").LeeTable({
                                        Col:<?php echo json_encode($coll);?>,
                                        IsEdit:true
                                    });
                                }
							
                                
                                
							});
						</script>
					</div><!-- /.row -->
					<!--lee 这里是中间内容部分 end-->
				</div><!-- /.page-content -->
			</div><!-- /.main-content -->
								<script type="text/javascript">
								
									$("#Form").validate({  
								        submitHandler: function() { 
								        	var data = $("#Form").serializeArray();
			                                var ldata = <?php echo json_encode($lead_data);?>; 
			                                var module = "<?php echo $module;?>";
			                                $.ajax({
			                                    'type':'post',
			                                    'data':{'data':data,
			                                     'ldata':ldata
			                                	},
			                                    'success':function(data){

			                                    	data = eval("("+data+")");
			                                    	
			                                        if(data.res=='suc'){
			                                        	alert(data.msg);
			                                        	if(module!='User'){
			                                        		location.href="<?php echo base_url();?>index.php/www/objects/lists?obj_type=<?php echo $obj_type;?>";
			                                        	}else{
			                                        		location.href="<?php echo base_url();?>index.php/www";

			                                        	}
			                                        	
			                                        }else{
			                                        	alert(data.msg);
			                                        }
			                                        
			                                    },
			                                    'url':"<?php echo base_url(); ?>index.php/www/objects/save",
			                                    'cache':false
			                                });
								        }  
								    });
								
			                        

			                    </script>

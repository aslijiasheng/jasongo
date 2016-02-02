<?php $random =rand(100,999);?>
<script type="text/javascript">
			                        $(document).ready(function () {
			                            $('[name=cancel]').click(function(){
			                            	$('#'+$DialogDivIDM).dialog("close");
			                            	});
			                            return false;
			                        });

			                    </script>
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
							<input type="hidden" name = "sobj_name" value = <?php echo $sobj_name;?>>
							<input type="hidden" name = "sobj_id" value = <?php echo $sobj_id;?>>
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
						
							<div class="col-xs-12" id="Ajax_add_<?php echo $random?>"></div>
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
								$("#Ajax_add_<?php echo $random?>").Format({
									type: "edit",
									FormatData: format_data,
									FromID:"Form",
									SelectChildEnumsUrl:'<?php echo base_url();?>index.php/admin/enum/SelectChildEnums'
								});
                                
							});
						</script>
					</div><!-- /.row -->
					<!--lee 这里是中间内容部分 end-->
					
				</div><!-- /.page-content -->
			</div><!-- /.main-content -->
								<script type="text/javascript">
			                        $(document).ready(function () {
			                            $('[name=save]').click(function(){
			                                var data = $("#Form").serializeArray();
			                                var ldata = <?php echo json_encode($lead_data)?>; 
			                                //console.debug(data);
			                                $.ajax({
			                                    'type':'post',
			                                    'data':{'data':data,'ldata':ldata},
			                                    'success':function(data){
			                                    	data = eval("("+data+")");
			                                    	
			                                        if(data.res=='suc'){
			                                        	alert(data.msg);
			                                        	$('#'+$DialogDivIDM).dialog("close");
                                                        window.location.replace(location.href);
			                                        }else{
			                                        	alert(data.msg);
			                                        }
			                                        
			                                    },
			                                    'url':"<?php echo base_url(); ?>index.php/www/objects/save",
			                                    'cache':false
			                                });
			                                 
			                            });
			                            return false;
			                            
			                        });

			                    </script>
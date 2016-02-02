
			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<!-- #section:basics/content.breadcrumbs -->
				<div class="breadcrumbs" id="breadcrumbs">
					<ul class="breadcrumb">
						<li>
							<i class="ace-icon fa fa-home home-icon"></i>
							<a href="#">首页</a>
						</li>
						<li class="active"><a href="<?php echo base_url();?>index.php/www/lead/list_json?>">线索列表</a></li>
						<li><?php echo $lead_data['Lead.Name'];?></li>
					</ul><!-- /.breadcrumb -->
					<!-- /section:basics/content.searchbox -->
				</div>

				<!-- /section:basics/content.breadcrumbs -->
				<div class="page-content">
					<!-- /section:settings.box -->
					<div class="page-header">
						<h1>
							<?php echo $lead_data['Lead.Name'];?>
							<small>
								Lead view
							</small>
						</h1>
					</div><!-- /.page-header -->
					
					<!--lee 这里是中间内容部分 start-->
					<div class="row">
						<form id="Form">
							<input type="hidden" name="<?php echo $primary['key'];?>" value="<?php echo $primary['value'];?>"/>
							<input type="hidden" name = "module_model" value="<?php echo $module;?>"/>
							<input type="hidden" name = "module_action" value="module_add"/>
							<div class="col-xs-12">
								<button class="btn btn-info" name="save">
									<i class="ace-icon fa fa-pencil align-top bigger-125"></i>
									保存
								</button>
								<button class="btn btn-danger">
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
								<script type="text/javascript">
			                        $(document).ready(function () {
			                            $('[name=save]').click(function(){
			                                var data = $("#Form").serializeArray();
			                                //console.debug(data);
			                                $.ajax({
			                                    'type':'post',
			                                    'data':{'data':data},
			                                    'success':function(data){
			                                        if(data==""){
			                                        	location.href="<?php echo base_url(); ?>index.php/www/"
			                                        }else{
			                                        	alert(data);
			                                        }
			                                        
			                                    },
			                                    'url':"<?php echo base_url(); ?>index.php/www/objects/save",
			                                    'cache':false
			                                });
			                                return false; 
			                            });
			                            false;
			                            
			                        });

			                    </script>
								<button class="btn btn-danger">
									<i class="ace-icon fa fa-pencil align-top bigger-125"></i>
									取消
								</button>
							</div>
						</form>
                        <?php //p(json_decode($col));?>
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

                                $("#detail").LeeTable({
                                    //TableHeight:364,
                                    //rowNum:10,
                                    //Url:"<?php echo base_url(); ?>index.php/www/objects/list_json?obj_type=3",
                                    /*
                                    Operation:[
                                        {Title:'查看',Css:'fa fa-search orange'}
                                        //{Title:'编辑',Url:'<?php echo site_url("www/objects/edit"); ?>?id={<?php echo $obj_data["KEY_ATTR_NAME"]; ?>}&obj_type=<?php echo $ObjType; ?>',Css:'fa fa-pencil blue'},
                                        //{Title:'删除',Url:'javascript:void(0);',Js:"dlt('{<?php echo $obj_data["KEY_ATTR_NAME"]; ?>}','<?php echo $ObjType; ?>','#LeeTable','<?php echo base_url(); ?>index.php/www/objects/list_json?obj_type=<?php echo $ObjType; ?>')",Css:'fa fa-trash-o red'},
                                        //{Title:'停用',Url:'javascript:void(0);',Css:'fa fa-power-off red',IfButton:[{If:"Lead.StopFlag",Etc:"是",Css:'fa fa-play green',Title:"启用"}]}
                                    ],
                                    */
                                    Col:<?php echo json_encode($col);?>,
                                    //MultiSelect:true, //是否开启全选功能
                                    //MultiType:0, //选着类型 0 多选（默认） 1 单选
                                    //KeyAttrName:'Lead.ID' //组件属性，用于给多选、单选、选中值等...
                                    IsEdit:true
                                });
                                /*
                                //加载明细
                                $.ajax({
                                    url:'<?php echo base_url();?>index.php/www/objects/detail?type_id=<?php  echo $_GET['type_id']?>&obj_type='+<?php echo $obj_type?>,
                                    success:function(data){
                                         $("#detail").html(data)
                                    }
                                })
                                */
							});
						</script>
					</div><!-- /.row -->
					<!--lee 这里是中间内容部分 end-->
					
				</div><!-- /.page-content -->
			</div><!-- /.main-content -->


	




			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<!-- #section:basics/content.breadcrumbs -->
				<div class="breadcrumbs" id="breadcrumbs">
					<ul class="breadcrumb">
						<li>
							<i class="ace-icon fa fa-home home-icon"></i>
							<a href="#">首页</a>
						</li>
						<li class="active">线索列表</li>
					</ul><!-- /.breadcrumb -->

					

					<!-- /section:basics/content.searchbox -->
				</div>

				<!-- /section:basics/content.breadcrumbs -->
				<div class="page-content">
					<!-- /section:settings.box -->
					<div class="page-header">
						<h1>
							线索列表
							<small>
								Lead List
							</small>
						</h1>
					</div><!-- /.page-header -->
					
					<!--lee 这里是中间内容部分 start-->
					<div class="row">
						
						<div class="col-xs-12">
							<a href="<?php echo site_url("www/objects/add?obj_type=91");?>"><button class="btn">
								<i class="ace-icon fa fa-pencil align-top bigger-125"></i>
								新建线索
							</button></a>
							<button id='lead_sale_seniorquery' class="btn btn-primary">
								<i class="ace-icon fa fa-pencil align-top bigger-125"></i>
								高级查询
							</button>
							<script type="text/javascript">
								//高级查询
								$(document).ready(function() {
									var seniorquery_attr = <?php echo $SeniorQueryAttrJson;?>;
									$("#lead_sale_seniorquery").SeniorQuery({
										SelectAttr: seniorquery_attr
									});

								});
							</script>
							<button class="btn btn-info">
								<i class="ace-icon fa fa-pencil align-top bigger-125"></i>
								批量导入
							</button>
							<button class="btn btn-success">
								<i class="ace-icon fa fa-pencil align-top bigger-125"></i>
								批量导出
							</button>
							<button class="btn btn-warning">
								<i class="ace-icon fa fa-pencil align-top bigger-125"></i>
								批量更新
							</button>
							<button class="btn btn-danger">
								<i class="ace-icon fa fa-pencil align-top bigger-125"></i>
								批量回收公共池
							</button>
							<button class="btn btn-inverse">
								<i class="ace-icon fa fa-pencil align-top bigger-125"></i>
								统计
							</button>
							<button class="btn btn-pink">
								<i class="ace-icon fa fa-pencil align-top bigger-125"></i>
								查重
							</button>
							<div class="hr hr-dotted hr-16"></div>
						</div>
						
						<div class="col-xs-12">
							<!-- PAGE CONTENT BEGINS -->
							<table id="grid-table"></table>

							<div id="grid-pager"></div>
							<!-- PAGE CONTENT ENDS -->
						</div><!-- /.col -->
					</div><!-- /.row -->
					<!--lee 这里是中间内容部分 end-->
					
				</div><!-- /.page-content -->
			</div><!-- /.main-content -->
					

		


		<script type="text/javascript">
			
			jQuery(function($) {
				var grid_selector = "#grid-table";
				var pager_selector = "#grid-pager";
				
				//resize to fit page size
				$(window).on('resize.jqGrid', function () {
					$(grid_selector).jqGrid( 'setGridWidth', $(".page-content").width() );
			    })
				//resize on sidebar collapse/expand
				var parent_column = $(grid_selector).closest('[class*="col-"]');
				$(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
					if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
						$(grid_selector).jqGrid( 'setGridWidth', parent_column.width() );
					}
			    })
				var url = "<?php echo base_url();?>index.php/www/objects/list_json?id=<?php echo $ObjType; ?>";
				var colNames = <?php echo $ColNames;?>;
				var colModel = <?php echo $ColModel;?>;
				var json_data = "";
				jQuery(grid_selector).jqGrid({
					url:url,
					mtype:"POST",
					datatype:"json",
					//postData:params,
					//width:10000;
					//autowidth:false,
					height: 420,
					colNames:colNames,
					colModel:colModel,
					viewrecords : true, //右下角的总数显示
					rowNum:10,
					pager : pager_selector,
					altRows: true,
					multiselect: true, //开启全选功能
			        multiboxonly: true,
					loadComplete : function() {
						var table = this;
						setTimeout(function(){
							updatePagerIcons(table);
						}, 0);
					},
					//获取传回来的JOSN数据
					beforeProcessing: function(xhr) {
						json_data = xhr;
					},
					gridComplete: function(){
						var gt	= $("#grid-table");
		                var ids = gt.getDataIDs();//jqGrid('getDataIDs');
						
						var view_url = "<?php echo site_url("www/lead/view");?>";
						var edit_url = "<?php echo site_url("www/lead/edit");?>";
						var del_url	 = "<?php echo site_url("www/lead/del");?>";

		                for(var i=0;i<ids.length;i++){
		                    var cl = ids[i];
							var param_id = json_data.rows[i].cell['<?php echo $obj_data["KEY_ATTR_NAME"];?>'];
		                    view = " <a href='"+view_url+"?id="+param_id+"' title='查看'><span class='ui-pg-div fa fa-search orange bigger-150'></span></a> ";
		                    edit = " <a href='"+edit_url+"?id="+param_id+"' title='编辑'><span class='fa fa-pencil blue bigger-150'></span></a> ";
		                    del = " <span class='fa fa-trash-o red bigger-150'></span> ";
		                    jQuery("#grid-table").jqGrid('setRowData',ids[i],{operation:view+edit+del});
		                } 
		            }
				});
				//留待后续使用
				$("#lead_sale_search").click(function(){
					var wsParams = {"Lead.Company":"厦门发鹭江商贸有限公司"};
					var gridParam = {url:url,datatype:"json",postData:wsParams,page:1};
					$(grid_selector).jqGrid('setGridParam', gridParam).trigger("reloadGrid");
				})
				
				//jQuery(grid_selector).jqGrid("hideCol", "<?php echo $obj_data['KEY_ATTR_NAME'];?>");

				function updatePagerIcons(table) {
					var replacement = 
					{
						'ui-icon-seek-first' : 'ace-icon fa fa-angle-double-left bigger-140',
						'ui-icon-seek-prev' : 'ace-icon fa fa-angle-left bigger-140',
						'ui-icon-seek-next' : 'ace-icon fa fa-angle-right bigger-140',
						'ui-icon-seek-end' : 'ace-icon fa fa-angle-double-right bigger-140'
					};
					$('.ui-pg-table:not(.navtable) > tbody > tr > .ui-pg-button > .ui-icon').each(function(){
						var icon = $(this);
						var $class = $.trim(icon.attr('class').replace('ui-icon', ''));
						
						if($class in replacement) icon.attr('class', 'ui-icon '+replacement[$class]);
					})
				}
				

				$(window).triggerHandler('resize.jqGrid');
			});
		</script>
			
		
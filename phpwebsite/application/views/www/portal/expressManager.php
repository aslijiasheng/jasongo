<!-- /section:basics/sidebar -->
<div class="main-content">
    <!-- #section:basics/content.breadcrumbs -->
    <div class="breadcrumbs" id="breadcrumbs">
            <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="<?php echo base_url(); ?>index.php/www/">首页</a>
            </li>

            <li class="active">快件管理</li>
        </ul><!-- /.breadcrumb -->



        <!-- /section:basics/content.searchbox -->
    </div>

    <!-- /section:basics/content.breadcrumbs -->
    <div class="page-content">
        <!-- /section:settings.box -->
        <div class="page-header">
            <h1>
            </h1>
        </div><!-- /.page-header -->


                <div class="row">
									<div class="col-xs-12">
										<!-- div.dataTables_borderWrap -->
										<div>
											<table id="dynamic-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>用户姓名</th>
														<th>用户手机号码</th>
														<th class="hidden-480">邮箱</th>
														<th>操作</th>
													</tr>
												</thead>

												<tbody>
<?php 
foreach($expressListUsers as $key => $listUsers){
?>
													<tr>

														<td> <?php echo $listUsers->user_name;?> </td>
														<td> <?php echo $listUsers->user_duty_name;?> </td>
														<td class="hidden-480">
															<span class="label label-sm label-warning"><?php echo $listUsers->user_email;?> </span>
														</td>
														<td>
															<div class="hidden-sm hidden-xs action-buttons">
                                                                <a alt="查看" name="viewExpressDetail" data-id="<?php echo $listUsers->user_id;?>" class="blue" href="#">
																	<i class="ace-icon fa fa-search-plus bigger-130"></i>
																</a>
                                                                <a alt="通知" name="takeExpressUser" data-id="<?php echo $listUsers->user_id;?>" class="blue" href="#">
                                                                    <i class="ace-icon fa fa-flag bigger-120"></i>
																</a>
															</div>


														</td>
													</tr>
    <?
}
?>
												</tbody>
											</table>
										</div>
									</div>
                    </div><!-- /.row -->
                    <div id="dialogExpressDetail" class="hide">
                    </div><!-- #dialog-message -->
</div><!-- /.page-content -->
</div><!-- /.main-content -->

		<script type="text/javascript">
			jQuery(function($) {
				//initiate dataTables plugin
				var oTable1 = 
				$('#dynamic-table')
				.dataTable( {
					bAutoWidth: false,
					"aaSorting": [],
			    } );
				$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('table')
					var off1 = $parent.offset();
					var w1 = $parent.width();
			
					var off2 = $source.offset();
					//var w2 = $source.width();
		
					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
                }

                viewExpressDetail();
                takeExpress();
            });

            function takeExpress(){
                $("a[name='takeExpressUser']").on("click", function(){
                    var data_id = $(this).data("id");
                    var url = '<?php echo site_url('www/express/expressTakeAction');?>';
                    var params = {takeUserID:data_id};
                    $.post(url, params, function(data){
                        if(data.succ == 1){
                            alert("通知发送成功");
                        } else {
                            alert("通知失败");
                        }
                    })
                });
            }

            

            function viewExpressDetail(){
                $("a[name='viewExpressDetail']").on("click", function(){
                    var data_id = $(this).data("id");
                    var url = '<?php echo site_url('www/express/expressTakeDetailAction');?>';
                    var params = {userID:data_id};
                    $.post(url, params, function(data){
                        $("#dialogExpressDetail").html(data);
                    })
                    var dialog = $("#dialogExpressDetail").removeClass('hide').dialog({
                        modal: true,
                        autoOpen: true,
                        width: "auto",
                        position: { my: "top", at: "top", of: window },
                        title: "快件操作",
                        title_html: true,
                        buttons: [ 
                            {
                                text: "Cancel",
                                "class" : "btn btn-minier",
                                click: function() {
                                    $("#dialogExpressDetail").html('');
                                    $(this).dialog("close"); 
                                } 
                            },
                            {
                                text: "OK",
                                "class" : "btn btn-primary btn-minier",
                                click: function() {
                                    $(this).dialog("close"); 
                                } 
                            }
                        ]
                    });
                })
            }
    </script>


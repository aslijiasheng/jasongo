                <div class="row">
									<div class="col-xs-12">
										<!-- div.dataTables_borderWrap -->
										<div>
											<table id="express-detail-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>代收件人</th>
														<th>到件时间</th>
														<th>快件状态</th>
														<th>操作</th>
													</tr>
												</thead>

												<tbody>
<?php 
if($expressTakeDetail === "null") die;
foreach($expressTakeDetail as $key => $expressTake){
?>
													<tr>

														<td> <?php echo $expressTake->express_create_user_id_attr->user_name;?> </td>
														<td> <?php echo $expressTake->express_create_date;?> </td>
														<td> <?php echo $expressTake->express_take_status_attr;?> </td>
														<td>
                                                        <?php if($expressTake->express_take_status != 10011) {?>
															<div class="hidden-sm hidden-xs action-buttons">
                                                                <a name="viewExpressTake" data-expressid="<?php echo $expressTake->express_id;?>" data-userid="<?php echo $expressTake->express_to_user_id;?>" class="blue" href="#">
																	<i class="ace-icon fa fa-search-plus bigger-130"></i>
																</a>
															</div>
                                                        <?php }?>
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

		<script type="text/javascript">
			jQuery(function($) {
				//initiate dataTables plugin
				var oTable1 = 
				$('#express-detail-table')
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

                viewExpressTake();
            })

            function viewExpressTake(){
                $("a[name='viewExpressTake']").on("click", function(){
                    var userid = $(this).data("userid");
                    var expressid = $(this).data("expressid");
                    var url = '<?php echo site_url('www/express/upExpressTakeAction');?>';
                    var params = {userID:userid, expressID:expressid};
                    $.post(url, params, function(data){
                        if(data.succ == 1){
                            $("#dialogExpressDetail").dialog("close"); 
                            alert("取件成功");    
                        } else {
                            alert("取件失败");
                        }
                    })
                })
            }
    </script>


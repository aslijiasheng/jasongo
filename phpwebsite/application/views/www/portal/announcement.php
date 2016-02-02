<!-- /section:basics/sidebar -->
<div class="main-content">
    <!-- #section:basics/content.breadcrumbs -->
    <div class="breadcrumbs" id="breadcrumbs">
            <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="<?php echo base_url(); ?>index.php/www/">首页</a>
            </li>

            <li class="active"><?php echo $obj[1]['LABEL'] ?>公告管理</li>
        </ul><!-- /.breadcrumb -->



        <!-- /section:basics/content.searchbox -->
    </div>

    <!-- /section:basics/content.breadcrumbs -->
    <div class="page-content">
        <!-- /section:settings.box -->
        <div class="page-header">
            <h1>
                <small>
                    <a href="#" id="addAnnouncement" class="btn btn-purple btn-sm">公告新增</a>
                </small>
            </h1>
        </div><!-- /.page-header -->


                <div class="row">
									<div class="col-xs-12">
										<!-- div.dataTables_borderWrap -->
										<div>
											<table id="dynamic-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>公告标题</th>
														<th>公告日期</th>
														<th class="hidden-480">标签</th>
														<th class="hidden-480">公告状态</th>
														<th>公告发布人</th>
														<th>操作</th>
													</tr>
												</thead>

												<tbody>
<?php 
foreach($announcementData as $key => $announcement){
?>
													<tr>

														<td> <?php echo $announcement['title'];?> </td>
														<td class="hidden-480"> <?php echo $announcement['createDate'];?> </td>
														<td class="hidden-480">
															<span class="label label-sm label-warning"><?php echo $announcement['tag'];?> </span>
														</td>
														<td class="hidden-480">
															<span class="label label-sm label-warning"><?php echo 't' === $announcement['isStatus'] ? '开启' : '关闭';?> </span>
														</td>
														<td class="hidden-480">
															<span class="label label-sm label-warning"><?php echo $announcement['userName'];?> </span>
														</td>
														<td>
															<div class="hidden-sm hidden-xs action-buttons">
                                                                <a name="viewAnnouncement" data-id="<?php echo $key;?>" class="blue" href="#">
																	<i class="ace-icon fa fa-search-plus bigger-130"></i>
																</a>

																<a name="editAnnouncement" data-id="<?php echo $key;?>" class="green" href="#">
																	<i class="ace-icon fa fa-pencil bigger-130"></i>
																</a>

																<a  name="delAnnouncement" data-id="<?php echo $key;?>" class="red" href="#">
																	<i class="ace-icon fa fa-trash-o bigger-130"></i>
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
                    <div id="dialogAnnouncement" class="hide">
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

                addAnnouncement();
                editAnnouncement();
                delAnnouncement();
                viewAnnouncement();
            })

            /**
             * 新增 
             */
            function addAnnouncement(){
                $("#addAnnouncement").on("click", function(){
                    var url = '<?php echo site_url('www/announcement/viewAnnouncement');?>';
                    var params = {};
                    $.post(url, params, function(data){
                        $("#dialogAnnouncement").html(data);
                    }).error(function(){
                        $("#dialogAnnouncement").html("网络请求失败");
                    })
                    var dialog = $("#dialogAnnouncement").removeClass('hide').dialog({
                        modal: true,
                        autoOpen: true,
                        width: "auto",
                        position: { my: "top", at: "top", of: window },
                        title: "公告操作",
                        title_html: true,
                        buttons: [ 
                            {
                                text: "Cancel",
                                "class" : "btn btn-minier",
                                click: function() {
                                    $("#viewAnnouncement").html('');
                                    $(this).dialog("close"); 
                                } 
                            },
                            {
                                text: "OK",
                                "class" : "btn btn-primary btn-minier",
                                click: function() {
                                    var announcementTitle = $("#announcementTitle").val();
                                    var announcementTag = $("#announcementTag").val();
                                    var announcementHtml = CKEDITOR.instances.editor.getData();
                                    var announcementPic = $("#announcementPic").val();
                                    var announcementCreateUserName = $("#announcementCreateUserName").val();
                                    var url = '<?php echo site_url("www/announcement/addAnnouncement");?>';
                                    var params = {announcement_title:announcementTitle, announcement_pic:announcementPic, announcement_tag:announcementTag, announcement_html:announcementHtml, announcement_create_user:announcementCreateUserName};
                                    $.post(url, params, function(data){
                                        console.debug(true==data);
                                        console.debug(data);
                                    })
                                    console.debug(CKEDITOR.instances.editor.getData());
                                    // $(this).dialog("close"); 
                                } 
                            }
                        ]
                    });
                })
            }
            /**
             * editAnnouncement 
             * 编辑
             * @access public
             * @return void
             */
            function editAnnouncement(){
                $("a[name='editAnnouncement']").on("click", function(){
                    var data_id = $(this).data("id");
                    var url = '<?php echo site_url('www/announcement/viewAnnouncement');?>';
                    var params = {announcement_id:data_id};
                    $.post(url, params, function(data){
                        $("#dialogAnnouncement").html(data);
                    })
                    var dialog = $("#dialogAnnouncement").removeClass('hide').dialog({
                        modal: true,
                        autoOpen: true,
                        width: "auto",
                        position: { my: "top", at: "top", of: window },
                        title: "公告操作",
                        title_html: true,
                        buttons: [ 
                            {
                                text: "Cancel",
                                "class" : "btn btn-minier",
                                click: function() {
                                    $("#viewAnnouncement").html('');
                                    $(this).dialog("close"); 
                                } 
                            },
                            {
                                text: "OK",
                                "class" : "btn btn-primary btn-minier",
                                click: function() {
                                    var announcementID = data_id;
                                    var announcementTitle = $("#announcementTitle").val();
                                    var announcementTag = $("#announcementTag").val();
                                    var announcementHtml = CKEDITOR.instances.editor.getData();
                                    var announcementPic = $("#announcementPic").val();
                                    var announcementCreateUserName = $("#announcementCreateUserName").val();
                                    var url = '<?php echo site_url("www/announcement/editAnnouncement");?>';
                                    var params = {announcement_id:announcementID, announcement_title:announcementTitle, announcement_pic:announcementPic, announcement_tag:announcementTag, announcement_html:announcementHtml,announcement_create_user:announcementCreateUserName};
                                    $.post(url, params, function(data){
                                        console.debug(true==data);
                                        console.debug(data);
                                    })
                                    // $(this).dialog("close"); 
                                } 
                            }
                        ]
                    });
                })
            }
            /**
             * delAnnouncement 
             * 删除 
             * @access public
             * @return void
             */
            function delAnnouncement(){
                $("a[name='delAnnouncement']").on("click", function(){
                    var data_id = $(this).data("id");
                    var url = '<?php echo site_url('www/announcement/viewAnnouncement');?>';
                    var data = '确认要删除此条公告信息吗?';
                    $("#dialogAnnouncement").html(data);
                    var dialog = $("#dialogAnnouncement").removeClass('hide').dialog({
                        modal: true,
                        autoOpen: true,
                        width: "auto",
                        position: { my: "top", at: "top", of: window },
                        title: "公告操作",
                        title_html: true,
                        buttons: [ 
                            {
                                text: "Cancel",
                                "class" : "btn btn-minier",
                                click: function() {
                                    $("#viewAnnouncement").html('');
                                    $(this).dialog("close"); 
                                } 
                            },
                            {
                                text: "OK",
                                "class" : "btn btn-primary btn-minier",
                                click: function() {
                                    var announcementID = data_id;
                                    var url = '<?php echo site_url("www/announcement/delAnnouncement");?>';
                                    var params = {announcement_id:announcementID};
                                    $.post(url, params, function(data){
                                        console.debug(true==data);
                                        console.debug(data);
                                    })
                                    $(this).dialog("close"); 
                                } 
                            }
                        ]
                    });
                })
            }
            /**
             * viewAnnouncement 
             * 预览
             * @access public
             * @return void
             */
            function viewAnnouncement(){
                $("a[name='viewAnnouncement']").on("click", function(){
                    var data_id = $(this).data("id");
                    var url = '<?php echo site_url('www/announcement/reviewAnnouncement');?>';
                    var params = {announcement_id:data_id};
                    $.post(url, params, function(data){
                        $("#dialogAnnouncement").html(data);
                    })
                    var dialog = $("#dialogAnnouncement").removeClass('hide').dialog({
                        modal: true,
                        autoOpen: true,
                        width: "auto",
                        position: { my: "top", at: "top", of: window },
                        title: "公告操作",
                        title_html: true,
                        buttons: [ 
                            {
                                text: "Cancel",
                                "class" : "btn btn-minier",
                                click: function() {
                                    $("#viewAnnouncement").html('');
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


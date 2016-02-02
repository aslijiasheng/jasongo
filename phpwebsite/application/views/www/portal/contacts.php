<!-- /section:basics/sidebar -->
<div class="main-content">
    <!-- #section:basics/content.breadcrumbs -->
    <div class="breadcrumbs" id="breadcrumbs">
            <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="<?php echo base_url(); ?>index.php/www/">首页</a>
            </li>

            <li class="active"><?php echo $obj[1]['LABEL'] ?>联系人通讯录</li>
        </ul><!-- /.breadcrumb -->



        <!-- /section:basics/content.searchbox -->
    </div>

    <!-- /section:basics/content.breadcrumbs -->
    <div class="page-content">
        <!-- /section:settings.box -->
        <div class="page-header">
            <h1>
                <small>
											<div class="btn-group">
												<button data-toggle="dropdown" class="btn btn-success btn-lg dropdown-toggle">
													通讯录操作
													<i class="ace-icon fa fa-angle-down icon-on-right"></i>
												</button>

												<ul class="dropdown-menu dropdown-success dropdown-menu-right">
													<li>
														<a class="dropdown-import" data-type="append" data-oper="import" href="#">通讯录导入(追加方式)</a>
													</li>

													<li>
														<a  class="dropdown-import"  data-type="truncat" data-oper="import" href="#">通讯录导入(覆盖方式)</a>
													</li>

													<li>
														<a class="dropdown-add" data-type="add" data-oper="add" href="#">新增</a>
													</li>

												</ul>
											</div><!-- /.btn-group -->
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
														<th>工号</th>
														<th>姓名</th>
														<th class="hidden-480">手机号码</th>
														<th class="hidden-480">坐机号码</th>
													</tr>
												</thead>

												<tbody>
<?php 
foreach($contantsData as $key => $contants){
?>
													<tr>

														<td> <?php echo $contants['contants_user_gonghao'];?> </td>
														<td class="hidden-480"> <?php echo $contants['contants_name'];?> </td>
														<td class="hidden-480">
															<span class="label label-sm label-warning"><?php echo $contants['contants_phone'];?> </span>
														</td>
														<td class="hidden-480">
															<span class="label label-sm label-warning"><?php echo $contants['contants_tel'];?> </span>
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
                    <div id="dialogContants" class="hide">
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

                addContants();
                importContants();
            });

            /**
             * importContants 
             * 操作通讯录
             * @access public
             * @return void
             */
            function importContants(){
                $("a[class='dropdown-import']").on("click", function(){
                    var data_type = $(this).data("type");
                    var data_oper = $(this).data("oper");
                    var url = '<?php echo base_url();?>index.php' + CONFIG.contant.importContant;
                    var importUrl = '<?php echo base_url();?>index.php' + CONFIG.contant.importContantAction;
                    var params = {oper:data_type};
                    $.post(url, params, function(data){
                        $("#dialogContants").html(data);
                    })
                    var dialog = $("#dialogContants").removeClass('hide').dialog({
                        modal: true,
                        autoOpen: true,
                        width: "auto",
                        position: { my: "top", at: "top", of: window },
                        title: "通讯录操作",
                        title_html: true,
                        buttons: [ 
                            {
                                text: "Cancel",
                                "class" : "btn btn-minier",
                                click: function() {
                                    $("#dialogContants").html('');
                                    $(this).dialog("close"); 
                                } 
                            },
                            {
                                text: "OK",
                                "class" : "btn btn-primary btn-minier",
                                click: function() {
                                    // $(this).dialog("close"); 
                                } 
                            }
                        ]
                    });
                })
            }
            /**
             * addContants 
             * 操作通讯录
             * @access public
             * @return void
             */
            function addContants(){
                $("a[class='dropdown-add']").on("click", function(){
                    var data_type = $(this).data("type");
                    var data_add = $(this).data("oper");
                    var url = '<?php echo base_url();?>index.php' + CONFIG.contant.addContant;
                    var addUrl = '<?php echo base_url();?>index.php' + CONFIG.contant.addViewAction;
                    var params = {};
                    $.post(url, params, function(data){
                        $("#dialogContants").html(data);
                    })
                    var dialog = $("#dialogContants").removeClass('hide').dialog({
                        modal: true,
                        autoOpen: true,
                        width: "auto",
                        position: { my: "top", at: "top", of: window },
                        title: "通讯录操作",
                        title_html: true,
                        buttons: [ 
                            {
                                text: "Cancel",
                                "class" : "btn btn-minier",
                                click: function() {
                                    $("#dialogContants").html('');
                                    $(this).dialog("close"); 
                                } 
                            },
                            {
                                text: "OK",
                                "class" : "btn btn-primary btn-minier",
                                click: function() {
                                    var phoneNumber    = $.trim($("#phoneNumber").val());
                                    var telPhoneNumber = $.trim($("#telPhoneNumber").val());
                                    var name           = $.trim($("#contantsName").val());
                                    var gonghao        = $.trim($("#contantsGonghao").val());
                                    console.debug(phoneNumber, telPhoneNumber, name, gonghao);
                                    //验证过程
                                    if("" == phoneNumber){
                                        alert("手机号码不能为空");
                                        return false;
                                    }
                                    if("" == telPhoneNumber){
                                        alert("固定号码不为空");
                                        return false;
                                    }
                                    if("" == name){
                                        alert("姓名不能为空");
                                        return false;
                                    }
                                    if("" == gonghao){
                                        alert("工号不能为空");
                                        return false;
                                    }
                                    if(isNaN(gonghao)){
                                        alert("手机号码错误");
                                        return false;
                                    }
                                    if(!checkPhone(phoneNumber)){
                                        alert("手机号码错误");
                                        return false;
                                    }
                                    if(!checkTelPhone(telPhoneNumber)){
                                        alert("固定电话号码错误");
                                        return false;
                                    }
                                    var addParams = {gonghao : gonghao, phone : phoneNumber, tel : telPhoneNumber, name : name};
                                    $.post(addUrl, addParams, function(data){
                                        console.debug(data);
                                    });
                                    // $(this).dialog("close"); 
                                } 
                            }
                        ]
                    });
                })
            }

            /**
             * checkPhone 
             * 验证手机号码
             * @param v $v 
             * @access public
             * @return void
             */
            function checkPhone(v){
                var isMob=/^((\+?86)|(\(\+86\)))?(13[012356789][0-9]{8}|15[012356789][0-9]{8}|18[02356789][0-9]{8}|147[0-9]{8}|1349[0-9]{7})$/;
                if(isMob.test(v)){
                    return true;
                }else{
                    return false;
                }
            }

            /**
             * checkTelPhone 
             * 验证电话号码
             * @param v $v 
             * @access public
             * @return void
             */
            function checkTelPhone(v){
                var isPhone = /^([0-9]{3,4}-)?[0-9]{7,8}$/;
                if(isPhone.test(v)){
                    return true;
                }else{
                    return false;
                }
            }
    </script>


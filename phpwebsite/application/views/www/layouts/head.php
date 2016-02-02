<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>TRUECOLOR</title>
		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?php echo base_url();?>style/ace/css/chosen.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>style/ace/css/jquery-ui.min.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>style/ace/css/bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>style/ace/css/font-awesome.min.css" />
		
		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="<?php echo base_url();?>style/ace/css/ui.jqgrid.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="<?php echo base_url();?>style/ace/css/ace-fonts.css" />
		<!-- ace styles -->
		<link rel="stylesheet" href="<?php echo base_url();?>style/ace/css/ace.min.css" />

		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="<?php echo base_url();?>style/ace/css/colorbox.css" />
		<link rel="stylesheet" href="http://localhost/skypro/ace-master/assets/css/dropzone.min.css" />
		<!--[if lte IE 9]>
			<link rel="stylesheet" href="../assets/css/ace-part2.min.css" />
		<![endif]-->
		<!-- lee
		<link rel="stylesheet" href="../assets/css/ace-skins.min.css" />
		<link rel="stylesheet" href="../assets/css/ace-rtl.min.css" />
		lee -->
		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="../assets/css/ace-ie.min.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- 左侧目录栏收缩功能的JS -->
		<script src="<?php echo base_url();?>style/ace/js/ace-extra.min.js"></script>

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="../assets/js/html5shiv.js"></script>
		<script src="../assets/js/respond.min.js"></script>
		<![endif]-->
		
		<link href="<?php echo base_url();?>style/ace/lee/css/Lee.css" rel="stylesheet" />

		<script src="<?php echo base_url();?>style/ace/js/jquery.min.js"></script>
		<!-- ace这个版本dialog有问题 改成新版本
		<script src="<?php echo base_url();?>style/ace/js/jquery-ui.min.js"></script>
		-->
		<script src="<?php echo base_url();?>style/js/jquery-ui.min.js"></script>

		<script src="<?php echo base_url();?>style/ace/js/chosen.jquery.min.js"></script>
		<script src="<?php echo base_url();?>style/ace/js/date-time/bootstrap-datepicker.min.js"></script>
		<script src="<?php echo base_url();?>style/ace/js/jqGrid/jquery.jqGrid.min.js"></script>
		<script src="<?php echo base_url();?>style/ace/js/jqGrid/i18n/grid.locale-cn.js"></script>
		
		<script src="<?php echo base_url();?>style/ace/js/jquery.validate.min.js"></script>

		<script src="<?php echo base_url();?>style/ace/js/jquery.colorbox-min.js"></script>
		<!-- page specific plugin scripts -->
		<script src="<?php echo base_url();?>style/ace/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>style/ace/js/jquery.dataTables.bootstrap.js"></script>
		<script src="<?php echo base_url();?>style/ace/js/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url();?>style/ace/js/dataTables.colVis.min.js"></script>

        <script src="<?php echo base_url();?>style/ckeditor/ckeditor.js"></script>
        <script src="<?php echo base_url();?>style/config.js"></script>

		<script src="http://localhost/skypro/ace-master/assets/js/dropzone.min.js"></script>
		<script src="<?php echo base_url();?>style/ace/lee/js/LeeUI.js"></script>
		<script>
			LeeUI('<?php echo base_url();?>');
		</script>
		
		<!-- page specific plugin scripts -->
	</head>
	<body class="no-skin">
		<!-- #section:basics/navbar.layout -->
		<div id="navbar" class="navbar navbar-default">
			<div class="navbar-container" id="navbar-container">
				<!-- /section:basics/sidebar.mobile.toggle -->
				<div class="navbar-header pull-left">
					<!-- #section:basics/navbar.layout.brand -->
					<a href="#" class="navbar-brand">
						<small>
							<i class="fa fa-leaf"></i>
							TRUECOLOR 
						</small>
					</a>

					<!-- /section:basics/navbar.layout.brand -->

					<!-- #section:basics/navbar.toggle -->

					<!-- /section:basics/navbar.toggle -->
				</div>

				<!-- #section:basics/navbar.dropdown -->
				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						<li class="grey">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="ace-icon fa fa-tasks"></i>
								<span class="badge badge-grey"><?php echo $message['count'];?></span>
							</a>

							<ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
								<li class="dropdown-header">
									<i class="ace-icon fa fa-check"></i>
									消息
								</li>
								<li class="dropdown-footer">
									<a href="#">
										查看所有消息
										<i class="ace-icon fa fa-arrow-right"></i>
									</a>
								</li>
							</ul>
						</li>

						<!-- 这里是关于右上角用户的一点东西 start -->
						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="<?php echo base_url();?>style/ace/avatars/user.jpg" alt="Jason's Photo" />
								<span class="user-info">
									<small>欢迎,</small>
									<?php echo $session['user_name']?>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a id='modify_password'>
										<i class="ace-icon fa fa-key"></i>
										修改密码
									</a>
								</li>

								<li>
									<a href="<?php echo base_url();?>index.php/www/objects/edit?id=<?php echo $session['user_id'];?>&obj_type=99">
										<i class="ace-icon fa fa-user"></i>
										个人资料
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="<?php echo base_url();?>index.php/www/login/logout">
										<i class="ace-icon fa fa-power-off"></i>
										退出
									</a>
								</li>
							</ul>
						</li>

						<!-- 这里是关于右上角用户的一点东西 end -->
					</ul>
				</div>

				<!-- /section:basics/navbar.dropdown -->
			</div><!-- /.navbar-container -->
		</div>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<!-- #section:basics/sidebar -->
			<div id="sidebar" class="sidebar responsive">
				<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						<button class="btn btn-success">
							<i class="ace-icon fa fa-signal"></i>
						</button>

						<button class="btn btn-info">
							<i class="ace-icon fa fa-pencil"></i>
						</button>

						<!-- #section:basics/sidebar.layout.shortcuts -->
						<button class="btn btn-warning">
							<i class="ace-icon fa fa-users"></i>
						</button>

						<button class="btn btn-danger">
							<i class="ace-icon fa fa-cogs"></i>
						</button>

						<!-- /section:basics/sidebar.layout.shortcuts -->
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->

				<ul class="nav nav-list" leeui-name="menu"></ul><!-- /.nav-list -->
				<script type="text/javascript">
					jQuery(function($) {
						//alert(1);
						function ui_menu(base_url,data,pid){
							if(pid==null){
								pid=0;
							}
							
							$.each(data,function(i,val){ //迭代  
								//alert(val);
								//当匹配这个上级ID就执行它
								if(val.pid == pid){
									//图标
									if(val.icon==null){
										icon_class = "";
									}else{
										icon_class = val.icon;
									}
									//地址
									if(val.url==null){
										url_href = "#";
									}else{
										if(val.url.substring(0,4)=="http"){
											url_href = val.url;
										}else{
											url_href = base_url+val.url;
										}
									}
									//创建页面
									if(pid==0){
										//alert(val.icon);
										$("[leeui-name='menu']").append('<li class="" id="menu_li_'+val.id+'"></li>');
										$("#menu_li_"+val.id).append('<a href="'+url_href+'" id="menu_a_'+val.id+'"><i class="menu-icon '+icon_class+'"></i><span class="menu-text">'+val.name+'</span></a>');
										$("#menu_li_"+val.id).append('<b class="arrow"></b>');
									}else{
										//这里要判断下这个ul是否存在
										//p($("#menu_ul_"+val.pid).length());
										if($("#menu_ul_"+val.pid).length < 1){
											$("#menu_a_"+val.pid).addClass("dropdown-toggle");
											//alert("#menu_li_"+val.pid);
											$("#menu_li_"+val.pid).append('<ul class="submenu" id="menu_ul_'+val.pid+'"></ul>');
											$("#menu_a_"+val.pid).append('<b class="arrow fa fa-angle-down"></b>');
										}
										$("#menu_ul_"+val.pid).append('<li class="" id="menu_li_'+val.id+'"><a href="'+url_href+'" id="menu_a_'+val.id+'"><i class="menu-icon '+icon_class+'"></i>'+val.name+'</a><b class="arrow"></b></li>');

									}
									ui_menu(base_url,data,val.id);
									if(val.active==1){
										ui_menu_active(val.id,data,'active')
									}
								}
							});
							
						}

						function ui_menu_active(id,data,active_class){
							if(active_class==null){
								active_class='active';
							}
							$.each(data,function(i,val){ //迭代 
								if(val.id == id){
									$("#menu_li_"+val.id).addClass(active_class);
									ui_menu_active(val.pid,data,'active open');
								}
							});
						}
						
						var menu_json = <?php echo $menu_json;?>;
						var base_url = '<?php echo base_url();?>index.php/';
						ui_menu(base_url,menu_json);

						$('#modify_password').click(function(){
							$.ajax({
                                'type': 'post',
                                'success': function (data) {
                                    $('#modify').html(data);
                                },
                                'url': '<?php echo base_url();?>index.php/www/user/modify_password',
                                'cache': false
                            });
                            $("#modify").dialog({
                                title: "修改密码",
                                modal: true,
                                width: 400,
                                height: 300,
                            });
                            $('#modify').dialog('open');
						});
					});
					
				</script>
<div id="modify"  style= "display:none"></div>
				<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
			</div>
			

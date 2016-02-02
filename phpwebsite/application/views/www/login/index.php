<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>登陆 - truecolor</title>

		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?php echo base_url();?>style/ace/css/bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>style/ace/css/font-awesome.min.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="<?php echo base_url();?>style/ace/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?php echo base_url();?>style/ace/css/ace.min.css" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?php echo base_url();?>style/ace/css/ace-part2.min.css" />
		<![endif]-->
		<link rel="stylesheet" href="<?php echo base_url();?>style/ace/css/ace-rtl.min.css" />

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="<?php echo base_url();?>style/ace/css/ace-ie.min.css" />
		<![endif]-->
		<link rel="stylesheet" href="<?php echo base_url();?>style/ace/css/ace.onpage-help.css" />

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lt IE 9]>
		<script src="<?php echo base_url();?>style/ace/js/html5shiv.js"></script>
		<script src="<?php echo base_url();?>style/ace/js/respond.min.js"></script>
		<![endif]-->
	</head>

	<body class="login-layout">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
								<h1>
									<i class="ace-icon fa fa-leaf green"></i>
									<span class="red">TrueColor</span>
									<span class="white" id="id-text2">用户登录</span>
								</h1>
								<h4 class="blue" id="id-company-text">&copy; TRUECOLOR</h4>
							</div>

							<div class="space-6"></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger">
												<i class="ace-icon fa fa-coffee green"></i>
												请输入你的信息
											</h4>
											<div class="space-6"></div>
											<form action="<?php echo base_url()?>index.php/www/login" method="post">
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input name="user[orgcode]" type="text" class="form-control" placeholder="单位简称" value="<?php if(isset($orgcode)){echo $orgcode;}else{echo "TrueColor";}?>" title="单位简称" data-rel="tooltip" data-placement="right"/>
															<i class="ace-icon fa fa-home"></i>
														</span>
													</label>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<select name="user[login_sys]" class="form-control" id="form-field-select-1" title="系统模块" data-rel="tooltip" data-placement="right">
																<option value="1" <?php if(isset($login_sys)){if($login_sys==1){echo 'selected="selected"';}}?>>订单管理</option>
																<option value="2" <?php if(isset($login_sys)){if($login_sys==2){echo 'selected="selected"';}}?>>门户管理</option>
															</select>
														</span>
													</label>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input name="user[login_name]" type="text" class="form-control" placeholder="登录名" value="<?php if(isset($login_name)){echo $login_name;}?>" title="登录名" data-rel="tooltip" data-placement="right"/>
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input name="user[password]" type="password" class="form-control" placeholder="密码" value="<?php if(isset($password)){echo $password;}?>" title="密码" data-rel="tooltip" data-placement="right"/>
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>

													<div class="space"></div>

													<div class="clearfix">
														<label class="inline">
															<input type="checkbox" class="ace" />
															<span class="lbl"> 记住账号</span>
														</label>

														<button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
															<i class="ace-icon fa fa-key"></i>
															<span class="bigger-110">登录</span>
														</button>
													</div>

													<div class="space-4"></div>
													<?php if($error!=""){?>
													<div class="alert alert-danger">
														<button type="button" class="close" data-dismiss="alert">
															<i class="ace-icon fa fa-times"></i>
														</button>
															<?php echo $error;?>
														<br />
													</div>
													<?php } ?>
												</fieldset>
											</form>

										</div><!-- /.widget-main -->

									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->

							</div><!-- /.position-relative -->
							
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo base_url();?>style/ace/js/jquery.min.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='<?php echo base_url();?>style/ace/js/jquery1x.min.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script src="<?php echo base_url();?>style/ace/js/bootstrap.min.js"></script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
			 $(document).on('click', '.toolbar a[data-target]', function(e) {
				e.preventDefault();
				var target = $(this).data('target');
				$('.widget-box.visible').removeClass('visible');//hide others
				$(target).addClass('visible');//show target
			 });
			});
			
			//you don't need this, just used for changing background
			jQuery(function($) {
				//默认给样式
				$('body').attr('class', 'login-layout blur-login');
				$('#id-text2').attr('class', 'white');
				$('#id-company-text').attr('class', 'light-blue');
				
				$('[data-rel=tooltip]').tooltip();

			});
		</script>
	</body>
</html>

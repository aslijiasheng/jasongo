<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	<!--<script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js">-->
    </script>
	<title>客户化平台</title>
	<link href="<?php echo base_url();?>style/admin/css/bootstrap/css/bootstrap.css" rel="stylesheet"></link>
	<link href="<?php echo base_url();?>style/admin/css/jquery-ui-bootstrap.css" rel="stylesheet"></link>
	<link href="<?php echo base_url();?>style/admin/css/style.css" rel="stylesheet"></link>
	<link href="<?php echo base_url();?>style/admin/css/main.css" rel="stylesheet"></link>
	<link href="<?php echo base_url();?>style/admin/css/lee.css" rel="stylesheet"></link>
	<script src="<?php echo base_url();?>style/admin/js/jquery.js"></script>
	<script src="<?php echo base_url();?>style/admin/js/jui/js/jquery-ui.min.js"></script>
	<script src="<?php echo base_url();?>style/admin/css/bootstrap/js/bootstrap.min.js"></script>

</head>
<body>
    <header>
      <a data-original-title="" href="index.php" class="logo">
        LOGO
      </a>

      <div class="user-profile">
        <a data-original-title="" data-toggle="dropdown" class="dropdown-toggle">
          <img src="<?php echo base_url();?>style/admin/css/img/profile1.png" alt="Profile-Image">
          <span class="caret"></span>
        </a>
        <ul class="dropdown-menu pull-right">
          <li>
            <a data-original-title="" href="#">
              Edit Profile
            </a>
          </li>
          <li>
            <a data-original-title="" href="#">
              Account Settings
            </a>
          </li>
          <li>
            <a data-original-title="" href="<?php echo site_url('admin/home/out') ?>">
              Logout
            </a>
          </li>
        </ul>
      </div>
      <ul class="mini-nav">
        <li>
          <a data-original-title="" href="#">
            <div class="fs1" aria-hidden="true" data-icon=""></div>
            <span class="info-label" id="quickMessages">16</span>
          </a>
        </li>
        <li>
          <a data-original-title="" href="#">
            <div class="fs1" aria-hidden="true" data-icon="&#xe0aa;"></div>
            <span class="info-label-green" id="quickAlerts">10</span>
          </a>
        </li>
        <li>
          <a data-original-title="" href="#">
            <div class="fs1" aria-hidden="true" data-icon=""></div>
            <span class="info-label-orange" id="quickShop">10</span>
          </a>
        </li>
      </ul>
    </header>




    <div class="container-fluid">
      <div class="dashboard-container">
        <div class="top-nav">
			<ul>
            <li>
              <a data-original-title="" name='taglink'  href="<?php echo site_url('admin/home/index') ?>" class="">
                <div class="fs1" aria-hidden="true" data-icon="&#xe000"></div>
				首页
              </a>
            </li>
            <li>
              <a data-original-title="" name='taglink'  href="<?php echo site_url('admin/object/index')?>" <?php if ($this->menu1=='cus'){echo 'class="selected"';}?>>
                <div class="fs1" aria-hidden="true" data-icon="&#xe0bb"></div>
                客户化平台
              </a>
            </li>
            <li>
              <a data-original-title="" name='taglink' href="<?php echo site_url('admin/template')?>" <?php if ($this->menu1=='template'){echo 'class="selected"';}?>>
                <div class="fs1" aria-hidden="true" data-icon="&#xe003"></div>
                模板管理
              </a>
            </li>
            <li>
              <a data-original-title="" name='taglink' href="<?php echo site_url('admin/menu/index')?>" <?php if ($this->menu1=='menu'){echo 'class="selected"';}?>>
                <div class="fs1" aria-hidden="true" data-icon="&#xe157"></div>
                菜单管理
              </a>
            </li>
            <li>
              <a data-original-title="" name='taglink' href="<?php echo site_url('admin/tools/index')?>" <?php if ($this->menu1=='tools'){echo 'class="selected"';}?>>
                <div class="fs1" aria-hidden="true" data-icon="&#xe08b"></div>
                开发工具管理
              </a>
            </li>
          </ul>
          <div class="clearfix">
          </div>
        </div>
        <div class="sub-nav">
      <?php if ($this->menu1=='tools'){ ?>
          <ul>
            <li>
              <a data-original-title="" href="<?php echo site_url('admin/tools/index')?>" <?php if ($this->menu2=='index'){echo 'class="selected"';}?>>
                工具首页
              </a>
            </li>
            <li>
              <a data-original-title="" href="<?php echo site_url('admin/timing_task/index') ?>" <?php if ($this->menu2=='timing_task'){echo 'class="selected"';}?>>
                定时任务
              </a>
            </li> 
          </ul>
      <?php }?>
		  <?php if ($this->menu1=='cus'){ ?>
          <ul>
            <li>
              <a data-original-title="" href="<?php echo site_url('admin/object/index')?>" <?php if ($this->menu2=='object'){echo 'class="selected"';}?>>对象管理</a>
            </li>
            <li>
              <a data-original-title="" href="<?php echo site_url('admin/attrtype/type_list') ?>" <?php if ($this->menu2=='attrtype'){echo 'class="selected"';}?>>
                属性类型管理
              </a>
            </li> 
          </ul>
          <?php }else if ($this->menu1=='template'){ ?>
			<ul>
				<li>
					<a data-original-title="" href="<?php echo site_url('admin/template/index?template_type=view')?>" <?php if ($this->menu2=='view'){echo 'class="selected"';}?>>
					视图 模板
					</a>
				</li>
				<li>
					<a data-original-title="" href="<?php echo site_url('admin/template/index?template_type=controller')?>" <?php if ($this->menu2=='controller'){echo 'class="selected"';}?>>控制器 模板</a>
				</li>
				<li>
					<a data-original-title="" href="<?php echo site_url('admin/template/index?template_type=model')?>" <?php if ($this->menu2=='model'){echo 'class="selected"';}?>>
					模型 模板
					</a>
				</li>
			</ul>
          <?php } ?>
          <input placeholder="先丢这里暂时不用" class="input-search hidden-phone" type="search">
          <div class="btn-group pull-right">
            <button class="btn btn-warning2">
              btn-warning2
            </button>
            <button data-toggle="dropdown" class="btn btn-warning2 dropdown-toggle">
              <span class="caret">
              </span>
            </button>
            <ul class="dropdown-menu pull-right">
              <li>
                <a href="" data-original-title="">
                  1
                </a>
              </li>
              <li>
                <a href="" data-original-title="">
                  2
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="dashboard-wrapper">
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<title>客户化平台</title>
	<link href="<?php echo base_url();?>style/admin/css/bootstrap/css/bootstrap.css" rel="stylesheet"></link>
	<link href="<?php echo base_url();?>style/admin/css/jquery-ui-bootstrap.css" rel="stylesheet"></link>
	<link href="<?php echo base_url();?>style/admin/css/style.css" rel="stylesheet"></link>
	<link href="<?php echo base_url();?>style/admin/css/main.css" rel="stylesheet"></link>
	<link href="<?php echo base_url();?>style/admin/css/lee.css" rel="stylesheet"></link>
	<link href="<?php echo base_url();?>style/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
	<script src="<?php echo base_url();?>style/admin/js/jquery.js"></script>
	<script src="<?php echo base_url();?>style/admin/js/jui/js/jquery-ui.min.js"></script>
	<script src="<?php echo base_url();?>style/admin/css/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>style/datetimepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
	<script type="text/javascript" src="<?php echo base_url();?>style/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
</head>
<body>
  <header>
    <a data-original-title="" href="index.php" class="logo">LOGO</a>

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
          {#foreach from=topmenu item=attr key=ell_k#}
            <li>
              <a data-original-title="" name='taglink'  href="<?php echo base_url().'index.php/';?>{#attr|menu_url#}" <?php if(strpos('{#attr|select#}',$this->menu1)>0){echo 'class="selected"';}?>>
                <div class="fs1" aria-hidden="true"><span class="{#attr|menu_icon#}" style="font-size:16px;"></span></div>{#attr|menu_name#}
              </a>
            </li>
          {#/foreach#}
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="sub-nav">
        <?php for($i=0; $i<count($submenu); $i++):?>
          <ul>
            <?php foreach($submenu[$i] as $k=>$v): ?>
              <li>
                <a data-original-title="" href="<?php echo site_url($v['menu_url']); ?>" <?php if ($this->menu1!='' and $this->menu1==$v['menu_label']){echo 'class="selected"';}?>><?php echo $v['menu_name'];?></a>
              </li>
            <?php endforeach;?>
          </ul>
        <?php endfor;?>
      </div>
      <script>
      $(".sub-nav ul").css("display","none");
      $(function(){
        //默认选择
        var select = $(".top-nav ul li a[class=selected]").parent().index();
        $(".sub-nav ul:eq("+select+")").css("display","block");

        $(".top-nav ul li").mouseenter(function(){
          n=$(this).index();
          // 撤销css
          $(".sub-nav ul").css("display","none");
          $(".top-nav ul li a").removeAttr("class", "selected");
          // 显示css
          $(this).children('a').attr("class", "selected");
          $(".sub-nav ul:eq("+n+")").css("display","block");
        });

        $("div.dashboard-container").mouseleave(function(){
          // 撤销css
          $(".sub-nav ul").css("display","none");
          $(".top-nav ul li a").removeAttr("class", "selected");

          $(".sub-nav ul:eq("+select+")").css("display","block");
          $(".top-nav ul li:eq("+select+")").children('a').attr("class", "selected");
        });

      })
      </script>
    </div>
    <div class="dashboard-wrapper">
<!doctype html>
<html>
<head>
<style>
	
.container-fluid {
   margin-top: 20px;
}
</style>
 <meta charset="utf-8">
 <title>无标题文档</title>
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" href="<?php echo base_url() ?>style/admin/css/bootstrap/css/bootstrap.css">
 <link href="<?php echo base_url() ?>style/admin/css/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">   	
  <script src="<?php echo base_url() ?>style/admin/css/bootstrap/js/jquery-1.8.3.min.js"></script>
  <script src="<?php echo base_url() ?>style/admin/css/bootstrap/js/bootstrap.js"></script>
  <link href="<?php echo base_url() ?>style/admin/css/fonts/font-awesome.min.css" rel="stylesheet">
</head>
<body>

	<div class="navbar navbar-inverse">
       <div class="navbar-inner">
			<div class="container">
			 
			
			<a class="brand" href="#">后台</a>
			 
			<!-- Everything you want hidden at 940px or less, place within here -->
			<ul class="nav pull-right">
                      
                     
                      <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">登陆<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                          <li><a href="#">Action</a></li>
                          <li><a href="#">Another action</a></li>
                          <li><a href="#">Something else here</a></li>
                          <li class="divider"></li>
                          <li><a href="#">Separated link</a></li>
                        </ul>
                      </li>
            </ul>
			 
			</div>
			</div>
    </div>

   
 


   

	<div class="modal" style="margin-top: 50px;">
      <div class="modal-header">
         <h3>登陆</h3>
      <br>
        	<?php if($this->session->userdata('error_message')!=null){echo "<span style='color:red' class='alert alert-danger'>".$this->session->userdata('error_message')."</span>";}?>
        
        </div>
	<form method="post" action="<?php echo site_url('admin/home/login') ?>" id="login-form">
		<p class="note">&nbsp;</p>
		<div class="modal-body">
			<div>
			  <label for="">用户名*</label><input type="text" name="au_name" class="form-control" value='<?php echo set_value('au_name') ?>'><?php echo "<span style='color:red'>".form_error('au_name')."</span>" ?>
		    </div>
		
		    <div>
			    <label for="">密码*</label><input type="password" name="au_password" id="" class="form-control" value='<?php echo set_value('au_password') ?>'><?php echo "<span style='color:red'>".form_error('au_password')."</span>" ?>
		    <div>
		</div>
		
			<div class="modal-footer">

				<input type="submit" name="submit" class='btn btn-success' value="login">
			</div>
				
		

	</form>
 </div><!-- form -->
			
	
   
</body>
</html>

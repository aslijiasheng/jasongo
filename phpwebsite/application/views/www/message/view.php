
<!-- /section:basics/sidebar -->
<div class="main-content">
    <!-- #section:basics/content.breadcrumbs -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="<?php echo base_url();?>index.php/www">首页</a>
            </li>
            <li class="active"><a href="<?php echo base_url();?>index.php/www/objects/lists?obj_type=<?php echo $obj_type;?>"><?php echo $dict; ?></a></li>
            <li><?php echo $lead_data[$OBJ_NAME.'.Subject'];?></li>
        </ul><!-- /.breadcrumb -->
        <!-- /section:basics/content.searchbox -->
    </div>

    <!-- /section:basics/content.breadcrumbs -->
    <div class="page-content">
        <!-- /section:settings.box -->
        <div class="page-header">
            <h1>
                <?php echo $lead_data[$OBJ_NAME.'.Subject'];?>
              
            </h1>
        </div><!-- /.page-header -->

        <!--lee 这里是中间内容部分 start-->
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-12">
                   <!-- <div class="btn-group">
                        <a class="btn btn-primary" data-toggle="dropdown">
                        
                            <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
                            新建消息
                            <span class="ace-icon fa fa-angle-down icon-only smaller-90"></span>
                        </a>
                    </div>
                    <?php array_shift($but_array); 
                    foreach($but_array as $k=>$v){
                        if( $obj_type != 1 && $obj_type != 3){
                           if($k == 6 ||$k == 7 ){
                                continue;
                            }
                        }
                        ?>
                        <div class="btn-group">
                            <a class="<?php echo $v['colour'];?>" <?php  if(isset($v['id'])) {echo "id=".$v['id'];}?> <?php  if(isset($v['url'])) {echo "href=".$v['url'];}?> <?php if(isset($v['dropdown'])){echo 'data-toggle="dropdown"';}?>>
                                <i class="ace-icon <?php echo $v['icon'];?> align-top bigger-125"></i>
                                <?php echo $v['name'];?>
                                <?php if(isset($v['dropdown'])){?>
                                    <span class="ace-icon fa fa-angle-down icon-only smaller-90"></span>
                                <?php }?>
                            </a>
                            <?php if(isset($v['dropdown'])){?>
                                <ul class="dropdown-menu dropdown-warning">
                                    <?php foreach($v['dropdown'] as $dk=>$dv){?>
                                        <li>
                                            <a <?php if(isset($dv['url'])) {echo "href=".$dv['url'];}?> <?php  if(isset($dv['id'])) {echo "id=".$dv['id'];}?>>
                                                <?php echo $dv["name"];?>
                                            </a>
                                            <?php if(isset($dv['javascript'])){?>
                                                <script type="text/javascript">
                                                    <?php echo $dv['javascript'];?>
                                                </script>
                                            <?php }?>
                                        </li>
                                    <?php }?>
                                </ul>
                            <?php }?>
                        </div>
                    <?php if(isset($v['javascript'])){?>
                        <script type="text/javascript">
                            <?php echo $v['javascript'];?>
                        </script>
                    <?php }?>
                    <?php }?>
                    <?php  if(count($private_but[$OBJ_NAME])){  ?>
                      <?php foreach($private_but[$OBJ_NAME] as $k=>$v){?>
                        <div class="btn-group">
                            <a class="<?php echo $v['colour'];?>" <?php  if(isset($v['id'])) {echo "id=".$v['id'];}?> <?php  if(isset($v['url'])) {echo "href=".$v['url'];}?> <?php if(isset($v['dropdown'])){echo 'data-toggle="dropdown"';}?>>
                                <i class="ace-icon <?php echo $v['icon'];?> align-top bigger-125"></i>
                                <?php echo $v['name'];?>
                                <?php if(isset($v['dropdown'])){?>
                                    <span class="ace-icon fa fa-angle-down icon-only smaller-90"></span>
                                <?php }?>
                            </a>
                            <?php if(isset($v['dropdown'])){?>
                                <ul class="dropdown-menu dropdown-warning">
                                    <?php foreach($v['dropdown'] as $dk=>$dv){?>
                                        <li>
                                            <a <?php if(isset($dv['url'])) {echo "href=".$dv['url'];}?> <?php  if(isset($dv['id'])) {echo "id=".$dv['id'];}?>>
                                                <?php echo $dv["name"];?>
                                            </a>
                                            <?php if(isset($dv['javascript'])){?>
                                                <script type="text/javascript">
                                                    <?php echo $dv['javascript'];?>
                                                </script>
                                            <?php }?>
                                        </li>
                                    <?php }?>
                                </ul>
                            <?php }?>
                        </div>
                    <?php if(isset($v['javascript'])){?>
                        <script type="text/javascript">
                            <?php echo $v['javascript'];?>
                        </script>
                    <?php }?>
                    <?php }?>
                    <?php }?>


                    <div class="hr hr-dotted hr-16"></div>
                </div>
                <div class="hr hr-dotted hr-16"></div>
            </div>-->

            <div id="ViewFormat" class="col-xs-12"></div><!-- /.col -->
            <script type="text/javascript">
                //编辑页面布局的table生成
                $(document).ready(function () {
                    $("#clues_into").AjaxDialog({
                        DialogUrl: "<?php echo base_url(); ?>index.php/www/lead/leadConversion",
                        DialogTitle: "线索转化",
                        DialogWidth: 450,
                        DialogHeight: 406,
                        DialogData: {id:<?php echo $this->input->get("id") ?>, obj_type:<?php echo $this->input->get("obj_type") ?>},
                        DialogButtons: [{
                                text: "确定",
                                Class: "btn btn-primary",
                                click: function () {
                                    var sub = true;
                                    if ($("#input_key_Account_Owner").val().length == 0) {
                                        sub = false;
                                        alert("请选择客户所有者");
                                        return;
                                    }
                                    if ($("#contact").prop("checked") && $("#input_key_Contact_Owner").val().length == 0) {
                                        sub = false;
                                        alert("请选择联系人所有者");
                                        return;
                                    }
                                    if ($("#opportunity").prop("checked") && $("#input_key_Opportunity_Owner").val().length == 0) {
                                        sub = false;
                                        alert("请选择联系人所有者");
                                        return;
                                    }
                                    if (sub) {
                                        // alert(1)
                                        //$(this).dialog("close");
                                        $("#cluesIntoData").submit();
                                    }
                                }

                            }]
                    })
                    var format_data = <?php echo json_encode($format_data); ?>;
                    $("#ViewFormat").Format({
                        type: "view",
                        FormatData: format_data
                    });
                });
            </script>
        </div><!-- /.row -->
        <div class="hr hr-dotted hr-16"></div>
        





        <!--lee 这里是中间内容部分 end-->
    </div><!-- /.page-content -->


</div><!-- /.main-content -->

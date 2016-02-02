
<!-- /section:basics/sidebar -->
<div class="main-content">
    <!-- #section:basics/content.breadcrumbs -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="#">首页</a>
            </li>
            <li class="active"><a href="<?php echo base_url();?>index.php/www/lead">线索列表</a></li>
            <li><?php echo $lead_data['Lead.Name'];?></li>
        </ul><!-- /.breadcrumb -->
        <!-- /section:basics/content.searchbox -->
    </div>

    <!-- /section:basics/content.breadcrumbs -->
    <div class="page-content">
        <!-- /section:settings.box -->
        <div class="page-header">
            <h1>
                <?php echo $lead_data['Lead.Name'];?>
                <small>
                    Lead view
                </small>
            </h1>
        </div><!-- /.page-header -->

        <!--lee 这里是中间内容部分 start-->
        <div class="row">

            <div class="col-xs-12">
                <button class="btn">
                    <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
                    新建线索
                </button>
                <button class="btn btn-info">
                    <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
                    批量导入
                </button>
                <button class="btn btn-success">
                    <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
                    批量导出
                </button>
                <button class="btn btn-warning">
                    <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
                    批量更新
                </button>
                <button class="btn btn-danger">
                    <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
                    批量回收公共池
                </button>
                <button class="btn btn-inverse">
                    <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
                    统计
                </button>
                <button class="btn btn-pink" id="clues_into">
                    <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
                    <!--								查重-->
                    线索转化
                </button>
                <div class="hr hr-dotted hr-16"></div>
            </div>

            <div id="ViewFormat" class="col-xs-12"></div><!-- /.col -->
            <script type="text/javascript">
                //编辑页面布局的table生成
                $(document).ready(function() {
                    $("#clues_into").AjaxDialog({
                        DialogUrl:"<?php echo base_url();?>index.php/www/lead/leadConversion",
                        DialogTitle:"线索转化",
                        DialogWidth:450,
                        DialogHeight:406,
                        DialogData:{id:<?php  echo $this -> input -> get("id")?>,obj_type:<?php  echo $this -> input -> get("obj_type")?>},
                        DialogButtons: [{
                            text:"确定",
                            Class:"btn btn-primary",
                            click: function(){
                                var sub = true;
                                if($("#input_key_Account_Owner").val().length == 0){
                                    sub = false;
                                    alert("请选择客户所有者");
                                    return;
                                }
                                if($("#contact").prop("checked") && $("#input_key_Contact_Owner").val().length == 0){
                                    sub = false;
                                    alert("请选择联系人所有者");
                                    return;
                                }
                                if($("#opportunity").prop("checked") && $("#input_key_Opportunity_Owner").val().length == 0){
                                    sub = false;
                                    alert("请选择联系人所有者");
                                    return;
                                }
                                if(sub){
                                    // alert(1)
                                    //$(this).dialog("close");
                                    $("#cluesIntoData").submit();
                                }
                            }

                        }]
                    })
                    var format_data = <?php echo json_encode($format_data);?>;
                    $("#ViewFormat").Format({
                        type: "view",
                        FormatData: format_data
                    });
                });
            </script>
        </div><!-- /.row -->
        <!--lee 这里是中间内容部分 end-->

    </div><!-- /.page-content -->
</div><!-- /.main-content -->

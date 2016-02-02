
<!-- /section:basics/sidebar -->
<div class="main-content">
    <!-- #section:basics/content.breadcrumbs -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="#">首页</a>
            </li>
            <li class="active"><a href="<?php echo base_url(); ?>index.php/www/lead/list_json?>">线索列表</a></li>
            <li><?php echo $lead_data['Lead.Name']; ?></li>
        </ul><!-- /.breadcrumb -->
        <!-- /section:basics/content.searchbox -->
    </div>

    <!-- /section:basics/content.breadcrumbs -->
    <div class="page-content">
        <!-- /section:settings.box -->
        <div class="page-header">
            <h1>
                <?php echo $lead_data['Lead.Name']; ?>
                <small>
                    Lead view
                </small>
            </h1>
        </div><!-- /.page-header -->

        <!--lee 这里是中间内容部分 start-->
        <div class="row">
            <form id="Form" >

                <input type="hidden" name="<?php echo $primary['key']; ?>" value="<?php echo $primary['value']; ?>"/>
                <input type="hidden" name = "module_model" value="<?php echo $module; ?>"/>
                <input type="hidden" name = "module_action" value="module_update"/>
                <div class="col-xs-12">
                    <button class="btn btn-info" name="save">
                        <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
                        保存
                    </button>
                    <button class="btn btn-danger">
                        <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
                        返回
                    </button>
                    <div class="hr hr-dotted hr-16"></div>
                </div>

                <div class="col-xs-12" id="EditFormat"></div>
                <div id="detail" class="col-xs-12"></div>
                <div class="col-xs-12">
                    <div class="hr hr-dotted hr-16"></div>
                    <button class="btn btn-info" name="save">
                        <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
                        保存
                    </button>
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $('[name=save]').click(function(){
                                var data = $("#Form").serializeArray();
                                //console.debug(data);
                                $.ajax({
                                    'type':'post',
                                    'data':{'data':data},
                                    'success':function(data){
                                        //alert(data);
                                    },
                                    'url':"<?php echo base_url(); ?>index.php/www/objects/save",
                                    'cache':false
                                });
                                return false; 
                            });
                            false;
                            
                        });

                    </script>
                    <button class="btn btn-danger">
                        <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
                        返回
                    </button>
                </div>
            </form>
            <script type="text/javascript">
                //编辑页面布局的table生成
                $(document).ready(function () {
                    var format_data = <?php echo json_encode($format_data); ?>;
                    $("#EditFormat").Format({
                        type: "edit",
                        FormatData: format_data,
                        FromID: "Form",
                        SelectChildEnumsUrl: '<?php echo base_url(); ?>index.php/admin/enum/SelectChildEnums'
                    });

                    $("#detail").LeeTable({

                        Col:<?php echo json_encode($col);?>,
                        IsEdit:true
                    });
                });
            </script>
        </div><!-- /.row -->
        <!--lee 这里是中间内容部分 end-->

    </div><!-- /.page-content -->
</div><!-- /.main-content -->


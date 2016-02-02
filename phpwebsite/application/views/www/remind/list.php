<!-- /section:basics/sidebar -->
<div class="main-content">
    <!-- #section:basics/content.breadcrumbs -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="#">首页</a>
            </li>
            <li class="active">消息列表</li>
        </ul><!-- /.breadcrumb -->



        <!-- /section:basics/content.searchbox -->
    </div>

    <!-- /section:basics/content.breadcrumbs -->
    <div class="page-content">
        <!-- /section:settings.box -->
        <div class="page-header">
            <h1>
                提醒列表
                <small>
                    Reminder List
                </small>
            </h1>
        </div><!-- /.page-header -->

        <!--lee 这里是中间内容部分 start-->
        <div class="row">

            <div class="col-xs-12">
                

            <div class="col-xs-12" id="message_page-content">
               
            </div><!-- /.col -->
        </div><!-- /.row -->
        <!--lee 这里是中间内容部分 end-->

    </div><!-- /.page-content -->
</div><!-- /.main-content -->
<div>
    <input type="text" value="" name="paging_no" id="paging_no"/>
    <input type="text" value="" name="paging_other" id="paging_other"/>
    <input type="text" value="" name="page_end" id="page_end"/>
</div>
<script type="text/javascript">
    jQuery(function ($) {
        var url = "<?php echo base_url(); ?>index.php/www/remind/ReminderList?obj_type=<?php echo $ObjType; ?>";
        var col = <?php echo json_encode($ColModel['Col']); ?>;
        var keyattrname = '<?php echo $ColModel['KeyAttrName'];?>';
        $("#message_page-content").LeeTable({
            Url:url,
            Operation:[
                {Title:'查看',Url:'{REMIND.url}',Css:'fa fa-search orange'},
                //{Title:'编辑',Url:'<?php echo site_url("www/objects/edit"); ?>?id={' + keyattrname + '}&obj_type=<?php echo $ObjType; ?>',Css:'fa fa-pencil blue'},
                {Title:'删除',Url:'javascript:void(0);',Js:"dlt('{<?php echo $obj_data["KEY_ATTR_NAME"]; ?>}','<?php echo $ObjType; ?>','#LeeTable','<?php echo base_url(); ?>index.php/www/objects/list_json?obj_type=<?php echo $ObjType; ?>')",Css:'fa fa-trash-o red'},
                //{Title:'停用/启用',Url:'javascript:void(0);',Css:'fa fa-power-off red',IfButton:[{If:"Lead.StopFlag",Etc:"是",Css:'fa fa-play green',Title:"启用"},{If:"Lead.StopFlag",Etc:"是",Css:'fa fa-play green',Title:"启用"}]}
            ],
            Col:col,
            MultiSelect:true, //是否开启全选功能
            MultiType:0, //选着类型 0 多选（默认） 1 单选
            KeyAttrName:keyattrname //组件属性，用于给多选、单选、选中值等...
        });
    });
   


</script>


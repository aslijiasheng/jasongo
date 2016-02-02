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
                消息列表
                <small>
                    Message List
                </small>
            </h1>
        </div><!-- /.page-header -->

        <!--lee 这里是中间内容部分 start-->
        <div class="row">

            <!--<div class="col-xs-12">
                <div class="btn-group">
                    <a class="btn btn-primary" data-toggle="dropdown">
                    
                        <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
                        新建消息
                        <span class="ace-icon fa fa-angle-down icon-only smaller-90"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-warning">
                        <li>
                            <a href="http://localhost:88/ncrm/index.php/www/message/add?obj_type=<?php echo $ObjType;?>&type_id=1"> 系统消息 </a>
                        </li>
                        <li>
                            <a href="http://localhost:88/ncrm/index.php/www/message/add?obj_type=<?php echo $ObjType;?>&type_id=2"> 用户消息 </a>
                        </li>
                    </ul>
                </div>
                <?php array_shift($but_array);
                foreach($but_array as $k=>$v){?>
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
                <div class="hr hr-dotted hr-16"></div>
            </div>-->

            <div class="col-xs-12" id="message_page-content">
               
            </div> <!-- /.col -->
            <!--<a id="GetSelectedID"> 获取选中的ID </a>
            <a id="GetSelectedAttr"> 获取选中的属性 </a>
            <a id="SetGridParam"> 刷新当前列表 </a>-->

        </div><!-- /.row -->
        <!--lee 这里是中间内容部分 end-->

    </div><!-- /.page-content -->
</div><!-- /.main-content -->

<script type="text/javascript">
    jQuery(function ($) {
        var url = "<?php echo base_url(); ?>index.php/www/message/list_json?obj_type=<?php echo $ObjType; ?>";
        var col = <?php echo json_encode($ColModel['Col']); ?>;
        var keyattrname = '<?php echo $ColModel['KeyAttrName'];?>';
        $("#message_page-content").LeeTable({
            Url:url,
            Operation:[
                {Title:'查看',Url:'<?php echo site_url("www/message/view"); ?>?id={' + keyattrname + '}&obj_type=<?php echo $ObjType; ?>',Css:'fa fa-search orange'},
                //{Title:'编辑',Url:'<?php echo site_url("www/objects/edit"); ?>?id={' + keyattrname + '}&obj_type=<?php echo $ObjType; ?>',Css:'fa fa-pencil blue'},
                {Title:'删除',Url:'javascript:void(0);',Js:"dlt('{<?php echo $obj_data["KEY_ATTR_NAME"]; ?>}','<?php echo $ObjType; ?>','#LeeTable','<?php echo base_url(); ?>index.php/www/objects/list_json?obj_type=<?php echo $ObjType; ?>')",Css:'fa fa-trash-o red'},
                //{Title:'停用',Url:'javascript:void(0);',Css:'fa fa-power-off red',IfButton:[{If:"Lead.StopFlag",Etc:"是",Css:'fa fa-play green',Title:"启用"}]}
            ],
            Col:col,
            MultiSelect:true, //是否开启全选功能
            MultiType:0, //选着类型 0 多选（默认） 1 单选
            KeyAttrName:keyattrname //组件属性，用于给多选、单选、选中值等...
        });
    });
   
        $('#GetSelectedID').click(function(){
            console.debug($("#<?php echo $objName[0]['LABEL'] ?>_page-content").LeeTable('GetSelectedID'));
        });
        $('#GetSelectedAttr').click(function(){
            console.debug($("#<?php echo $objName[0]['LABEL'] ?>_page-content").LeeTable('GetSelectedAttr'));
        });
        $('#SetGridParam').click(function(){
            $("#<?php echo $objName[0]['LABEL'] ?>_page-content").LeeTable('SetGridParam');
        });

</script>


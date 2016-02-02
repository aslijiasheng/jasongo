<div class="row">
    <div id="detail" class="col-xs-12"></div>
</div>

<script type="text/javascript">
    jQuery(function () {
        $("#detail").LeeTable({
            //TableHeight:364,
            //rowNum:10,
            //Url:"<?php echo base_url(); ?>index.php/www/objects/list_json?obj_type=3",
            /*
             Operation:[
             {Title:'查看',Css:'fa fa-search orange'}
             //{Title:'编辑',Url:'<?php echo site_url("www/objects/edit"); ?>?id={<?php echo $obj_data["KEY_ATTR_NAME"]; ?>}&obj_type=<?php echo $ObjType; ?>',Css:'fa fa-pencil blue'},
             //{Title:'删除',Url:'javascript:void(0);',Js:"dlt('{<?php echo $obj_data["KEY_ATTR_NAME"]; ?>}','<?php echo $ObjType; ?>','#LeeTable','<?php echo base_url(); ?>index.php/www/objects/list_json?obj_type=<?php echo $ObjType; ?>')",Css:'fa fa-trash-o red'},
             //{Title:'停用',Url:'javascript:void(0);',Css:'fa fa-power-off red',IfButton:[{If:"Lead.StopFlag",Etc:"是",Css:'fa fa-play green',Title:"启用"}]}
             ],
             */
            Col:<?php echo json_encode($col);?>,
            //MultiSelect:true, //是否开启全选功能
            //MultiType:0, //选着类型 0 多选（默认） 1 单选
            //KeyAttrName:'Lead.ID' //组件属性，用于给多选、单选、选中值等...
            IsEdit:true
        });
    })
</script>


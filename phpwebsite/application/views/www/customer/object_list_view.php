<?php  foreach ($res as $k => $v) { ?>
    <div class="widget-box" >
        <div class="widget-header widget-header-flat">
            <h5 class="widget-title"><?php echo $v['LABEL']; ?></h5>
            <!-- #section:custom/widget-box.toolbar -->
            <div class="widget-toolbar">
                <a href="#" data-action="collapse">
                    <!--i class="ace-icon fa fa-chevron-up"></i-->
                    <i class="ace-icon fa fa-plus" data-icon-hide="fa-minus" data-icon-show="fa-plus"></i>
                </a>
            </div>
            <div class="widget-toolbar no-border">
                <div class="widget-menu">
                    <?php if(isset($v['btn_action'])){?>
                        <?php foreach ($v['btn_action']['buttons'] as $btn_k => $btn_v) {
                            if (isset($btn_v['url'])) { ?>
                                <a data-action="settings">
                                    <i id="<?php echo $v['REL_LIST_NAME'].'_but_'.$btn_k;?>_ajax_lists" class="ace-icon fa fa-bars"><?php echo $btn_v['name'];?></i>
                                </a>
                                <script type="text/javascript">
                                    $("#<?php echo $v['REL_LIST_NAME'].'_but_'.$btn_k;?>_ajax_lists").click(function(){
                                        var id = <?php echo $id;?>;
                                        var obj_type = <?php echo $v['OBJ_TYPE'];?>;
                                        var url = "<?php echo $btn_v['url'];?>";
                                        $DialogDivIDW = "DialogDiv" + $(this).attr("id");
                                        if ($('#' + $DialogDivIDW).length == 0) {
                                            $('#dialogdiv').after('<div id="' + $DialogDivIDW + '" style="display:none;"></div>');
                                        }

                                        $.ajax({
                                            'type': 'post',
                                            'success': function (data) {
                                                $('#' + $DialogDivIDW).html(data);
                                            },
                                            'url': url,
                                            'cache': false
                                        });
                                        $("#" + $DialogDivIDW).dialog({
                                            title: "<?php echo $btn_v['name'];?>",
                                            modal: true,
                                            width: 900,
                                            height: 600,
                                            buttons:[{
                                                text:'确认',
                                                Class:"btn btn-primary",
                                                click: function(){
                                                    var radio = $('#'+$DialogDivIDW).find('input[type=radio]:checked');
                                                    var radio_val = radio.val();
                                                    var radio_name = radio.attr("data-name");
                                                    //赋值
                                                    $.ajax({
                                                        'type':'post',
                                                        'data':{'id':id,'obj_type':obj_type,'rel_id':radio_val},
                                                        'success':function(data){
                                                            data = eval("("+data+")");
                                                            if(data.res=='fail'){
                                                                alert(data.msg);
                                                            }else{
                                                                $('#'+$DialogDivIDW).dialog("close");
                                                                window.location.replace(location.href);
                                                            }
                                                            
                                                        },
                                                        url:'<?php echo $btn_v['save_url'];?>'
                                                        
                                                    });
                                                    
                                                }
                                            },{
                                                text:'取消',
                                                Class:"btn btn-primary",
                                                click: function(){
                                                    $(this).dialog("close");
                                                }
                                            }]
                                        });
                                        $('#' + $DialogDivIDW).dialog('open');
                                    });
                                        
                                    </script>
                                <?php } else { ?>
                                <a data-action="settings" data-toggle="dropdown">
                                    <i class="ace-icon fa fa-bars"><?php echo $btn_v['name'];?></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right dropdown-light-blue dropdown-caret dropdown-closer">
                                    <?php foreach ($btn_v['sub'] as $sub_k => $sub_v) {?>
                                    <li>
                                        <a id="<?php echo $v['REL_LIST_NAME'].'_sub_'.$sub_k;?>_ajax_add" data-toggle="tab" ><?php echo $sub_v['name'];?></a>
                                    </li>
                                    <script type="text/javascript">
                                    $("#<?php echo $v['REL_LIST_NAME'].'_sub_'.$sub_k;?>_ajax_add").click(function(){
                                        var id = <?php echo $id;?>;
                                        var sobj_type = <?php echo $v['OBJ_TYPE'];?>;
                                        var url = "<?php echo $sub_v['url'];?>";

                                        // window.open(url);

                                        
                                        $DialogDivIDM = "DialogDiv" + $(this).attr("id");
                                        if ($('#' + $DialogDivIDM).length == 0) {
                                            $('#dialogdiv').after('<div id="' + $DialogDivIDM + '" style="display:none;"></div>');
                                        }
                                        $.ajax({
                                            'type': 'post',
                                            'data': {'id':id,'sobj_type':sobj_type},
                                            'success': function (data) {
                                                
                                                $('#' + $DialogDivIDM).html(data);
                                            },
                                            'url': url,
                                            'cache': false
                                        });
                                        $("#" + $DialogDivIDM).dialog({
                                            title: "新增",
                                            modal: true,
                                            width: 1000,
                                            height: 800,
                                        
                                        });
                                        $('#' + $DialogDivIDM).dialog('open');
                                    });
                                        
                                    </script>
                                <?php } ?>
                        
                                </ul>
                                <?php } } ?>
                    <?php } ?>
                    
                </div>
                <a href="#" data-action="fullscreen" class="orange2">
                    <i class="ace-icon fa fa-expand"></i>
                </a>
                <a href="#" data-action="reload">
                    <i class="ace-icon fa fa-refresh"></i>
                </a>
            </div>
            <!-- /section:custom/widget-box.toolbar -->
        </div>
        <div class="widget-body">
            <div class="widget-main padding-4">
                <div class="my-content" id="<?php echo $v['REL_LIST_NAME']; ?>-my-reluser-content">    
                </div>

            </div>
        </div>
    </div>
<?php } sleep(1); ?>

<script type="text/javascript">
     //$('.widget-box').addClass('collapsed');
</script>


<?php foreach ($res as $key => $value) { ?>
    <script type="text/javascript">
        $('#<?php echo $value['REL_LIST_NAME']; ?>-my-reluser-content').ace_scroll({
            horizontal: true,
            size: 1800,
            //styleClass: 'scroll-top',
            //mouseWheelLock: true
        }).css('padding-bottom', 15);
    </script>
    <script type="text/javascript">
        var params = <?php echo json_encode($value); ?>;
        var url = '<?php echo site_url('www/objects/ajax_view_lists') . "?id=" . $id."&obj_type=".$obj_type ;?>'
        //"http://localhost:7777/index.php/www/objects/ajax_view_lists?id=<?php echo $id; ?>"
        $("#<?php echo $value['REL_LIST_NAME']; ?>-my-reluser-content").AjaxHtml({
            Url: url,
            Data: params
        });

    </script>
<?php } ?>
<div id='dialogdiv'></div>
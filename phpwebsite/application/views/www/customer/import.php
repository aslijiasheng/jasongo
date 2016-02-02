<div id="my-wizard" data-target="#step-container">
    <ul class="wizard-steps">
       <li data-target="#step1" class="active">
          <span class="step">1</span>
          <span class="title">步骤一</span>
       </li>
       <li data-target="#step2">
          <span class="step">2</span>
          <span class="title">步骤二</span>
       </li>
       <li data-target="#step3">
          <span class="step">3</span>
          <span class="title">导入完成</span>
       </li>
    </ul>
</div>

<div class="step-content" id="step-container">
    <form class="form-horizontal" id="sample-form" method= "post" enctype="multipart/form-data" action="<?php echo base_url();?>index.php/www/objects/importfile">
        <div class="step-pane active" id="step1">
            <input type="hidden" name="obj_type" value="<?php echo $obj_type?>">
            <!-- step 1 -->
<pre>1. 你可以把csv文件、制表符分隔的文件或其它分隔符分隔的数据文件导入系统。
2. 请确认文件格式、分隔符和字符集与你要导入的数据文件相符。
3. 一次导入操作最好不要超过2000-3000条记录。
4. 如果需要导入大的数据文件，可以将其拆成若干个小的文件然后分别导入。</pre>
                <div class="form-group">
                    <label for="suffix" class="col-sm-3 control-label no-padding-right">指定导入文件格式：</label>
                    <div class="col-sm-3">
                        <select id="suffix" class="col-sm-12" name="file-type">
                            <option value="csv">CSV(*.csv)</option>
                            <option value="xls">Excel(*.xls)</option>
                            <option value="xlsx">Excel2007(*.xlsx)</option>
                        </select>
                    </div>
                    <div class="help-block col-sm-6">(暂时只支持csv格式 默认为逗号分隔)</div>
                </div>
                <div class="form-group">
                    <label for="symbol" class="col-sm-3 control-label"></label>
                    <div class="col-sm-9">
                        <label>
                          <input type="radio" name="symbol" class="ace" value="," checked/>
                          <span class="lbl">逗号分隔的文件</span>
                        </label>
                        <label>
                          <input type="radio" name="symbol" class="ace" value="|" />
                          <span class="lbl">制表符分隔的文件</span>
                        </label>
                        <label>
                          <input type="radio" name="symbol" class="ace" value=""/>
                          <span class="lbl">自定义分隔符分隔的文件</span>
                          <input type="text" value="," maxlength="1" style="width:40px;" name="userdelimited"/>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    
                    <label for="my-file-input" class="col-sm-3 control-label no-padding-right">选中导入文件：</label>
                    <div class="col-sm-5">
                            <input type="file" onchange="ajaxFileUpload(this)" class="uploadFile" name="myfile" id="myfile">
                            <input type="hidden"  id="file_addr" name="file_addr">
                            <span style="display: none; width: 200px;color: red">档案上传中，请稍后</span>
                    </div>
                    <div class="col-sm-4">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-9">
                        <label>
                          <input type="checkbox" name="with_title" class="ace" value="1" checked/>
                          <span class="lbl">有标题栏</span>
                        </label>
                        <label>
                          <input type="checkbox" name="create_enum" class="ace" value="1"/>
                          <span class="lbl">创建不存在的枚举值</span>
                        </label>
                    </div>
                </div>
            
            
        </div>
        <div class="step-pane" id="step2">
            <!-- step 2 -->

        </div>
        <div class="step-pane" id="step3">
            <!-- step 3 -->
            请选择必须填写的数据
        </div>
    </form>
</div>

<div class="wizard-actions">
    <button class="btn-prev btn">
        <i class="ace-icon fa fa-arrow-left"></i> 上一步
    </button>
    <button class="btn-next btn btn-success" data-last="完成" >
        下一步 <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
    </button>
</div>

<script type="text/javascript">
    jQuery(function($) {
        
        $('#my-file-input')
        .ace_file_input()
        .on('change', function() {
            files = $(this).data('ace_input_files');  
            
        });

        $('#my-wizard')
        .ace_wizard({
            //step: 2 //optional argument. wizard will jump to step "2" at first
        })
        .on('change' , function(e, info) {
            //info.step
            //info.direction
            //console.debug(e);
            //console.debug(info);
            //alert(1);
            if(info.step==1 && info.direction == 'next'){
                var file_url =$('#file_addr').val();
                 //alert(files.filelist.0.mozFullPath);
                $.ajax({
                   'type':'post',
                    data:{'file_url':file_url},
                    'success':function(data){
                        $('#step2').html(data);
                    },
                    'url':'<?php echo base_url();?>index.php/www/objects/importfile?obj_type=<?php echo $obj_type;?>',
                    'cache':false
                });
            }
            if(info.step==2 && info.direction == '完成'){
                $.ajax({
                    'type':'post',
                    'success':function(data){
                        $('#step2').html(data);
                    },
                    'url':'<?php echo base_url();?>index.php/www/objects/save?obj_type=<?php echo $obj_type;?>',
                    'cache':false
                });
            }
            if(info.step==2 && info.direction == 'next'){
                    var v = true;
                    $(".must").each(function(){
                        if($(this).val() == "null"){
                            v = false;
                            return;
                        }
                    })
                    if(v){
                        var dataInfo = $("#importRelation").serialize().toString();
                        //console.log(dataInfo)
                        $.ajax({
                            'type':'post',
                            'data':{data:dataInfo},
                            'success':function(data){
                                $('#step3').html(data);
                            },
                            'url':'<?php echo base_url();?>index.php/www/objects/saveBatchLead?obj_type=<?php echo $obj_type;?>',
                            'cache':false
                        });
                    }else{
                        alert("请填写数据")
                    }

            }
        })
        .on('finished', function(e) {
           //do something when finish button is clicked
           alert(2);
           $(this).closest("[data-name='DialogDiv']").dialog("close");
        }).on('stepclick', function(e) {
           //e.preventDefault();//this will prevent clicking and selecting steps
           alert(3);
        });

        
    });

    function ajaxFileUpload(This){
        var thisNext=$(This). next();
        var thisNextSpan= $(This). next(). next()
        thisNextSpan.css('display','inline-block');
        var  valid=true;
        var fileId=null;
        var fileValues='';
        if($(This).val().length!=0){
            fileValues=$(This).val();
            $(This).prev().prev().val(fileValues)
            fileId=$(This).attr('id')
            if(thisNext.val().length!=0){
                var Hidden=thisNext.val();
            }
        }else{
            valid=false;
        }
//        if((fileValues.indexOf('.csv')==-1&&fileValues.indexOf('.xls')==-1&&fileValues.indexOf('.xlsx')==-1))
//        {
//            valid=false;
//        }
        if(valid){
            $.ajaxFileUpload({
                url:'<?php echo base_url();?>index.php/www/objects/upload',
                secureuri:false,
                dataType: 'json',
                fileElementId:"myfile",
                success: function (data)
                {
                     //console.log(data);
                    //数据回调
                    thisNextSpan.css('display','none');
                    if(data.full_path != null)
                    {
                         thisNext.val(data.full_path);

                    }else{
                         //数据错误时 打印错误
                         alert(data.error);
                    }
                },
                error: function (data,status, e)
                {
                    alert(e);
                }
            })
        }else{
            thisNextSpan.css('display','none');
        }
    }

</script>
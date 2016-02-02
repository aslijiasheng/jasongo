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
          <span class="title">导出完成</span>
       </li>
    </ul>
</div>

<div class="step-content" id="step-container">
    <form class="form-horizontal" id="sample-form" method= "post" enctype="multipart/form-data" action="<?php echo base_url();?>index.php/www/objects/export_csv">
        <div class="step-pane active" id="step1">
            <input type="hidden" name="obj_type" value="<?php echo $obj_type?>">
            <!-- step 1 -->
            <pre>请选择需要导出的数据。</pre>
                <div class="form-group">
                    
                    <div class="radio">
          						<label>
          							<input class="ace" type="radio" name="export_list" value="0">
          							<span class="lbl"> 导出选中记录 </span>
          							<input type="hidden" name="selected_data" value="<?php echo $selected_data?>">
          						</label>
          					</div>
          					<div class="radio">
          						<label>
          							<input class="ace" type="radio" name="export_list" value="1">
          							<span class="lbl"> 导出当前列表 </span>
          						</label>
          					</div>
                  
                
	               </div>
            
            
        </div>
        <div class="step-pane" id="step2">
            <!-- step 2 -->
            

        </div>
        <div class="step-pane" id="step3">
            <!-- step 3 -->
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
                var data2 = $("input[type=radio]:checked").val();
                var selected_data = <?php echo $selected_data;?>;
                alert(selected_data);
                if(data2 == ""){
                  alert("请选择需要导出的数据。");
                  wizard.currentStep = 1;
                  wizard.setState();
                }
                
                if(data2 != 1 ){
                  
                  if(selected_data == null || selected_data == '' || selected_data == undefined){

                    alert("请勾选需要导出的数据。");
                    
                    wizard.currentStep = 1;
                    wizard.setState();
                  }
                }
                
                $.ajax({
                   'type':'post',
                    data:{
                        'export_all':data2,
                        'selected_data':selected_data
                    },

                    'success':function(data){
                        $('#step2').html(data);
                    },
                    'url':'<?php echo base_url();?>index.php/www/objects/exportm?obj_type=<?php echo $obj_type;?>',
                    'cache':false
                });
            }
            if(info.step==2 && info.direction == 'next'){
                var data="";
                $('#to option').each(function(k,v){
                  
                  if(data==""){
                      data = $(this).val();
                  }else{
                      data = data+","+$(this).val();
                  }
                });
                var data2 = $("input[type=radio]:checked").val();
                var selected_data = <?php echo $selected_data;?>;
                $.ajax({
                    'type':'post',
                    'data':{
                      'selected_label':data,
                      'export_all':data2,
                      'selected_data':selected_data,
                    },
                    'success':function(data){
                        $('#step3').html(data);
                    },
                    'url':'<?php echo base_url();?>index.php/www/objects/exportfile?obj_type=<?php echo $obj_type;?>',
                    'cache':false
                });
            }
        })
        .on('finished', function(e) {
           //do something when finish button is clicked
           $(this).closest("[data-name='DialogDivexport']").dialog("close");
        }).on('stepclick', function(e) {
           //e.preventDefault();//this will prevent clicking and selecting steps
           alert(3);
        });

        
    });


</script>
 	




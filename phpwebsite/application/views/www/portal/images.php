<!-- /section:basics/sidebar -->
<div class="main-content">
    <!-- #section:basics/content.breadcrumbs -->
    <div class="breadcrumbs" id="breadcrumbs">
            <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="<?php echo base_url(); ?>index.php/www/">首页</a>
            </li>

            <li class="active"><?php echo $obj[1]['LABEL'] ?>列表</li>
        </ul><!-- /.breadcrumb -->



        <!-- /section:basics/content.searchbox -->
    </div>

    <!-- /section:basics/content.breadcrumbs -->
    <div class="page-content">
        <!-- /section:settings.box -->
        <div class="page-header">
            <h1>
                <small>
                    <a href="#" id="multifile" class="btn btn-purple btn-sm">批量上传</a>
                    <div id="multifile-message" class="hide">
                    </div><!-- #dialog-message -->
                </small>
            </h1>
        </div><!-- /.page-header -->


                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <div>
                            <ul class="ace-thumbnails clearfix">
                            <?php foreach($data as $key => $value){?>
                                <li>
                                <a href="<?php echo $value['banner_pic'];?>" data-rel="colorbox">
                                            <img width="150" height="150" alt="150x150" src="<?php echo $value['banner_pic'];?>" />
                                            <div class="text">
                                                <div class="inner">图片管理</div>
                                            </div>
                                        </a>

                                        <div class="tools tools-bottom">
                                            <a href="#" name="portalImage" id="<?php echo $value['banner_id'];?>">
                                                <i class="ace-icon fa fa-times red"></i>
                                            </a>
                                        </div>
                                    </li>

                                <?php }?>
                                </ul>
                            </div><!-- PAGE CONTENT ENDS -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
</div><!-- /.page-content -->
</div><!-- /.main-content -->


    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        jQuery(function($) {
            $("a[name='portalImage']").on("click", function(){
                var portalId = this.id;
                var portalUrlDel = '<?php echo site_url('www/banner/delBanner');?>';
                var portalParams = {portalId:portalId};
                $.post(portalUrlDel, portalParams, function(data){
                    if(data){
                        window.location.href = '<?php echo site_url('www/banner/images');?>';
                    }else{
                        alert("删除失败，请联系管理员");
                    }
                }).error(function(){
                    alert("出错了！");
                })
            })
				$( "#multifile" ).on('click', function(e) {
					e.preventDefault();
                    var url = '<?php echo site_url("www/banner/multifile");?>';
                    var params = {};
                    $.post(url, params, function(data){
                        $("#multifile-message").html(data);
                    })
			
					var dialog = $( "#multifile-message" ).removeClass('hide').dialog({
						modal: true,
                        autoOpen: true,
                        width: "auto",
                        position: { my: "top", at: "top", of: window },
						title: "图片上传",
						title_html: true,
						buttons: [ 
							{
								text: "Cancel",
								"class" : "btn btn-minier",
								click: function() {
									$( this ).dialog( "close" ); 
								} 
							},
							{
								text: "OK",
								"class" : "btn btn-primary btn-minier",
								click: function() {
									$( this ).dialog( "close" ); 
								} 
							}
						]
					});
			
					/**
					dialog.data( "uiDialog" )._title = function(title) {
						title.html( this.options.title );
					};
					**/
				});
var $overflow = '';
var colorbox_params = {
    rel: 'colorbox',
    reposition:true,
    scalePhotos:true,
    scrolling:false,
    previous:'<i class="ace-icon fa fa-arrow-left"></i>',
    next:'<i class="ace-icon fa fa-arrow-right"></i>',
    close:'&times;',
    current:'{current} of {total}',
    maxWidth:'100%',
    maxHeight:'100%',
    onOpen:function(){
        $overflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
    },
    onClosed:function(){
        document.body.style.overflow = $overflow;
    },
    onComplete:function(){
        $.colorbox.resize();
    }
};

$('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
	$("#cboxLoadingGraphic").html("<i class='ace-icon fa fa-spinner orange fa-spin'></i>");//let's add a custom loading icon
	
	
	$(document).one('ajaxloadstart.page', function(e) {
		$('#colorbox, #cboxOverlay').remove();
   });
})
		</script>

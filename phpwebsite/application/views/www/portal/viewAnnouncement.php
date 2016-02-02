
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form">

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> 标题 </label>

										<div class="col-sm-9">
                                        <input type="text" id="announcementTitle" placeholder="标题" class="form-control" value="<?php echo $announcementData[$announcementID]['title'];?>" />
										</div>
									</div>

									<div class="space-4"></div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right">标签</label>

										<div class="col-sm-9">
											<span class="input-icon">
												<input type="text" id="announcementTag" value="<?php echo $announcementData[$announcementID]['tag'];?>"/>
												<i class="ace-icon fa fa-leaf blue"></i>
											</span>

										</div>
									</div>

									<div class="space-4"></div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-tags">公告内容</label>

										<div class="col-sm-9">
                                            <div id="editor">
                                            <?php echo $announcementData[$announcementID]['htmlPath'];?>
                                            </div>
										</div>
									</div>
                                    <input type="hidden" name="announcementPic" id="announcementPic" value="<?php echo $announcementData[$announcementID]['pic'];?>"/>
			</div>
		</div><!-- /.main-container -->
<script>
if ( CKEDITOR.env.ie && CKEDITOR.env.version < 9 )
	CKEDITOR.tools.enableHtml5Elements( document );

CKEDITOR.config.height = 150;
CKEDITOR.config.width = 'auto';

var initSample = ( function() {
	var wysiwygareaAvailable = isWysiwygareaAvailable(),
		isBBCodeBuiltIn = !!CKEDITOR.plugins.get( 'bbcode' );

	return function() {
		var editorElement = CKEDITOR.document.getById( 'editor' );

		if ( isBBCodeBuiltIn ) {
			editorElement.setHtml(
				'请输入公告内容'
			);
		}

		if ( wysiwygareaAvailable ) {
			CKEDITOR.replace( 'editor' );
		} else {
			editorElement.setAttribute( 'contenteditable', 'true' );
			CKEDITOR.inline( 'editor' );
		}
	};

	function isWysiwygareaAvailable() {
		if ( CKEDITOR.revision == ( '%RE' + 'V%' ) ) {
			return true;
		}

		return !!CKEDITOR.plugins.get( 'wysiwygarea' );
	}
} )();

	initSample();
function innerAnnouncement(announcementPic){
    var _announcementPic = $("#announcementPic").val();
    _announcementPic += "," + announcementPic;
    $("#announcementPic").val(_announcementPic);
}
</script>

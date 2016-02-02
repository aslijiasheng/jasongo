
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form">

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> 标题 </label>

										<div class="col-sm-9">
<?php echo $announcementData[$announcementID]['title'];?>
										</div>
									</div>

									<div class="space-4"></div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right">发布人</label>

										<div class="col-sm-9">
											<span class="col-sm-9">
<?php echo $announcementData[$announcementID]['userName'];?>
											</span>

										</div>
									</div>

									<div class="space-4"></div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right">标签</label>

										<div class="col-sm-9">
											<span class="input-icon">
<?php echo $announcementData[$announcementID]['tag'];?>
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
			</div>
		</div><!-- /.main-container -->

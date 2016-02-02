<div class="all-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-header">
					<div class="title">
						{#obj_label#}新建页面
						<span class="mini-title">
						{#obj_name#}
						</span>
					</div>
					<span class="tools">
						<!-- 窗口按钮部门
						<a class="fs1" data-icon="&#xe090;" aria-hidden="true" data-original-title=""></a>
						-->
					</span>
				</div>
				<div class="widget-body">
					<div class="row-fluid">
						<div class="leebutton">
<!--lee 按钮部分 start-->
<a id="yw0" class="btn btn-primary" href="<?php echo site_url('www/{#obj_name#}/')?>">
<i class="icon-undo"></i>
返回{#obj_label#}列表
</a>
<!--lee 按钮部分 end-->
						</div>
						<div id="divmessagelist">
							<div>
<!--lee 内容部分 start-->
<div class="grid-view">
	<form class="form-horizontal" id="department-form" action="<?php if(!isset($_GET['type_id'])){echo site_url('www/{#obj_name#}/add');}else{echo site_url('www/{#obj_name#}/add?type_id='.$_GET['type_id']);}?>" method="post">
		 	<?php if({#is_obj_type#}==1){?>
		 	<?php if(!isset($_GET['type_id'])){ ?>
		 	  {#foreach from=edit_layout.layout_arr item=lll key=lll_k#}
						<div class="control-group">
							<label class="control-label required" for="{#obj_name#}_{#lll|attr_name#}">
								<?php echo $labels["{#lll|attr_id_arr.attr_field_name#}"];?>
								{#if {#lll|is_required#}==1#}<span class="required">*</span>{#else#}<span class="required"></span>{#/if#}
							</label>
							<div class="controls">
								{#include views/attrtype/edit/{#lll|attr_id_arr.attr_type_arr.attrtype_edit_template#}#}
								<span class="help-inline"></span>
							</div>
						</div>
						{#/foreach#}
		 	<?php }else{ ?>
		    <?php switch ($_GET["type_id"]){ 
							case -1:
							break;
						?>
						{#foreach1 from=type_arr item=ta key=ta_k#}
						<?php case {#ta|type_id#}: ?>
						{#foreach2 from=edit_layout.layout_arr item=lll key=lll_k#}
						<div class="control-group">
							<label class="control-label required" for="{#obj_name#}_{#lll|attr_name#}">
								<?php echo $labels["{#lll|attr_id_arr.attr_field_name#}"];?>
								{#if {#lll|is_required#}==1#}<span class="required">*</span>{#else#}<span class="required"></span>{#/if#}
							</label>
							<div class="controls">
								{#include views/attrtype/edit/{#lll|attr_id_arr.attr_type_arr.attrtype_edit_template#}#}
								<span class="help-inline"></span>
							</div>
						</div>
						{#/foreach2#}
				       <?php break; ?>
						{#/foreach1#}
						<?php default: ?>
						{#foreach from=edit_layout.layout_arr item=lll key=lll_k#}
						<div class="control-group">
							<label class="control-label required" for="{#obj_name#}_{#lll|attr_name#}">
								<?php echo $labels["{#lll|attr_id_arr.attr_field_name#}"];?>
								{#if {#lll|is_required#}==1#}<span class="required">*</span>{#else#}<span class="required"></span>{#/if#}
							</label>
							<div class="controls">
								{#include views/attrtype/edit/{#lll|attr_id_arr.attr_type_arr.attrtype_edit_template#}#}
								<span class="help-inline"></span>
							</div>
						</div>
						{#/foreach#}
					<?php }?>
                <?php }}else{ ?>
                  {#foreach from=edit_layout.layout_arr item=lll key=lll_k#}
						<div class="control-group">
							<label class="control-label required" for="{#obj_name#}_{#lll|attr_name#}">
								<?php echo $labels["{#lll|attr_id_arr.attr_field_name#}"];?>
								{#if {#lll|is_required#}==1#}<span class="required">*</span>{#else#}<span class="required"></span>{#/if#}
							</label>
							<div class="controls">
								{#include views/attrtype/edit/{#lll|attr_id_arr.attr_type_arr.attrtype_edit_template#}#}
								<span class="help-inline"></span>
							</div>
						</div>
						{#/foreach#}
                 <?php }?>

		<!-- 按钮区域 start -->
		<div class="form-actions">
			<button class="btn btn-primary" type="submit">保存</button>
		</div>
		<!-- 按钮区域 end -->
	</form>
</div>
<!--lee 内容部分 end-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
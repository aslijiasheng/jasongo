
<div class="main-content">
<div class="row">
		<div class="col-sm-10 col-sm-offset-1">
			<div class="widget-box transparent invoice-box">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="widget-box transparent invoice-box">
							<div class="widget-body">
								<div class="widget-main padding-24">

								<?php $i=0;extract($tables[0], EXTR_OVERWRITE); ?>
									<table class="table table-striped table-bordered">

										<tr>
										<?php foreach ($cells as $k => $v) {?>
											
											<td rowspan="<?php echo $v['rowspan']?>" colspan="<?php echo $v['colspan']?>" >
												
												<?php if($v['label']==1){?>
													<div ><?php echo $v[$v['name']]['label'];?></div>
												<?php }else{?><div class=""> <?php if(isset($v[$v['name'].".ENUM_VALUE"])){echo $v[$v['name'].".ENUM_VALUE"];}elseif(isset($v[$v['name'].".Name"])){echo $v[$v['name'].".Name"];}else{echo $v['content'];}?></div>
												<?php }?>
												
											</td>
										<?php
											$i=$i+$v['colspan'];
											if($i % $columns == 0 ){
											echo '</tr>';
											}
										}?></tr>
									</table>
									</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
		


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
													<label for="<?php echo $v['name']?>"><?php echo $v[$v['name']]['label'];?></label>
												<?php }else{?><input id="<?php echo $v['name'];?>"  value="<?php echo $v['content'];?>" required="<?php if($v['required']){echo"required";}?>"/>
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
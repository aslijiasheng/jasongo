<!--lee 内容部分 start-->
<div class="grid-view">
	<form class="form-horizontal" id="salespay-form" action="<?php echo site_url('www/salespay/add');?>" method="post">
		<div class="control-group">
			<label class="control-label required" for="salespay_create_time">
				订单信息
				<span class="required"></span>
			</label>
			<div class="controls">
				<div class="group-text">
					<table class="table table-bordered">
						<tbody>
							<tr>
								<td width="10%">订单总金额</td>
								<td width="10%"><?php echo $order_data['order_amount'];?></td>
								<td width="10%">可到帐金额</td>
								<td width="10%"><?php echo $order_data['order_amount'];?></td>
							<tr>
							</tr>
								<td width="10%">已确认到帐</td>
								<td width="10%"><?php echo $order_data['order_amount'];?></td>
								<td width="10%">未确认到帐</td>
								<td width="10%"><?php echo $order_data['order_amount'];?></td>
								
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		
		<!--
		<div class="control-group">
			<label class="control-label required" for="salespay_create_time">
				订单总金额
				<span class="required"></span>
			</label>
			<div class="controls">
				<div class="group-text">
					<?php echo $order_data['order_amount'];?>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label required" for="salespay_create_time">
				已确认到帐
				<span class="required"></span>
			</label>
			<div class="controls">
				<div class="group-text">
					<?php echo $order_data['order_amount'];?>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label required" for="salespay_create_time">
				未确认到帐
				<span class="required"></span>
			</label>
			<div class="controls">
				<div class="group-text">
					<?php echo $order_data['order_amount'];?>
				</div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label required" for="salespay_create_time">
				可到帐金额
				<span class="required"></span>
			</label>
			<div class="controls">
				<div class="group-text">
					<?php echo $order_data['order_amount'];?>
				</div>
			</div>
		</div>
		-->
		<div class="control-group">
			<label class="control-label required" for="salespay_pay_amount">
				本次入账金额
				<span class="required"></span>
			</label>
			<div class="controls">
				<input size="16" type="text" name="salespay[salespay_pay_amount]">
			</div>
		</div>


						
						<div class="control-group">
							<label class="control-label required" for="salespay_pay_date">
								<?php echo $labels["salespay_pay_date"];?>
								<span class="required"></span>
							</label>
							<div class="controls">
								<div class="input-append date form_date" id="salespay_pay_date">
									<input size="16" type="text" name="salespay[salespay_pay_date]" style="width: 154px;" value="">
									<span class="add-on"><i class="icon-close"></i></span>
									<span class="add-on"><i class="icon-clock"></i></span>
								</div>
<script type="text/javascript">
$(document).ready(function () {
	$('#salespay_pay_date').datetimepicker({
        language:  'zh-CN',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 1,
		forceParse: 0,
		format:'yyyy-mm-dd hh:ii:ss'
    });
});
</script>
								<span class="help-inline"></span>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label required" for="salespay_pay_method">
								<?php echo $labels["salespay_pay_method"];?>
								<span class="required"></span>
							</label>
							<div class="controls">
								<select id="salespay_pay_method" name="salespay[salespay_pay_method]">
									<?php foreach($salespay_pay_method_enum as $pay_method_v){ ?>
									<option value="<?php echo $pay_method_v['enum_key'];?>">
										<?php echo $pay_method_v['enum_name'];?>
									</option>
									<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="control-group">
							<div class="controls">
<div id="remit_info" class="well payinfo">
	<div class="form-horizontal">
		<div class="control-group">
			<label class="control-label" style="width: 120px;">汇款账户：</label>
			<div class="controls" style="margin-left: 120px;">
				<select id="country" name="payinfo[remit_bank]">
					<option value="1001">
						工商银行（公司）
					</option>
					<option value="1002">
						工商银行（个人）
					</option>
					<option value="1003">
						招商银行
					</option>
					<option value="1004">
						建设银行
					</option>
					<option value="1005">
						农业银行
					</option>
					<option value="1006">
						中国银行
					</option>
					<option value="1007">
						Paypal（贝宝）
					</option>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" style="width: 120px;">汇款人姓名：</label>
			<div class="controls" style="margin-left: 120px;">
				<input type="text" name="payinfo[remit_person]">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" style="width: 120px;">汇款人银行账号：</label>
			<div class="controls" style="margin-left: 120px;">
				<input type="text" name="payinfo[remit_account]">
			</div>
		</div>
	</div>
</div>
<div id="alipay_info" class="well payinfo"  style="display:none;">
	<div class="control-group">
		<label class="control-label" style="width: 120px;">入帐支付宝帐号：</label>
		<div class="controls" style="margin-left: 120px;">
			<select id="country" name="payinfo[remit_bank]">
				<option value="1001">
					payment@shopex.cn
				</option>
				<option value="1002">
					alipay_f@shopex.cn
				</option>
				<option value="1003">
					wanggou@shopex.cn
				</option>
				<option value="1004">
					tp_taobao@shopex.cn
				</option>
				<option value="1005">
					shangpai@shopex.cn
				</option>
				<option value="1006">
					weishangye@shopex.cn
				</option>
				<option value="1007">
					b2btaobao@shopex.cn
				</option>
				<option value="1008">
					sms_alipay@shopex.cn
				</option>
				<option value="1009">
					wdwd_alipay@shopex.cn
				</option>
				<option value="1010">
					iwancu@shopex.cn
				</option>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" style="width: 120px;">交易号：</label>
		<div class="controls" style="margin-left: 120px;">
			<input type="text" name="payinfo[alipay_order]">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" style="width: 120px;">支付宝账号：</label>
		<div class="controls" style="margin-left: 120px;">
			<input type="text" name="payinfo[alipay_account]">
		</div>
	</div>
</div>
<div id="check_info" class="well payinfo" style="display:none;">
	<div class="control-group">
		<label class="control-label" style="width: 120px;">支票名称：</label>
		<div class="controls" style="margin-left: 120px;">
			<input type="text" name="payinfo[check_name]">
		</div>
	</div>
</div>
<div id="cash_info" class="payinfo" style="display:none;"> </div>
								
							</div>
						</div>
<script type="text/javascript">
$(document).ready(function () {
	$('#salespay_pay_method').change(function(){
		//获取选择的value用作判断
		enumkey = $(this).val();
		if(enumkey==1001){
			$(".payinfo").hide();
			$("#remit_info").show();
		}
		if(enumkey==1002){
			$(".payinfo").hide();
			$("#alipay_info").show();
		}
		if(enumkey==1003){
			$(".payinfo").hide();
			$("#check_info").show();
		}
		if(enumkey==1004){
			$(".payinfo").hide();
			$("#cash_info").show();
		}
	});
});
</script>
						<div class="control-group">
							<label class="control-label required" for="salespay_pay_note">
								<?php echo $labels["salespay_pay_note"];?>
							</label>
							<div class="controls">
								<textarea name="salespay[salespay_pay_note]" id="salespay_pay_note" style="width:400px" rows="6"></textarea>
							</div>
						</div>
						
		 	
		<!-- 按钮区域 start -->
		<!--
		<div class="form-actions">
			<button class="btn btn-primary" type="submit">保存</button>
		</div>
		-->
		<!-- 按钮区域 end -->
	</form>
</div>
<!--lee 内容部分 end-->
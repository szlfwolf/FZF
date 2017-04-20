<?php include_once 'header.php'; ?>


<div id="page-wrapper">


	<div class="container-fluid">
		<div class="row" style="margin-top: 20px;">

			<div class="panel panel-default">
				<div class="panel-heading">查询条件</div>
				<div class="panel-body">

					<form class="form-inline" method="post" role="form">
						<div class="form-group">
							<label for="start_date">开始时间</label> <input type="text"
								class="form-control" id="start_date" name="start_date" onClick="WdatePicker()" value=<?php echo $sdate ?>
								>
						</div>
						<div class="form-group">
							<label for="end_date">结束时间</label> <input type="text"
								class="form-control" id="end_date" name="end_date" onClick="WdatePicker()" value=<?php echo $edate ?>
								>
						</div>
						<button type="submit" class="btn btn-default">查询</button>
					</form>


				</div>
			</div>

			<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th>结算日期</th>
							<th>会员名称</th>
							<th>交易量</th>
							<th>成交笔数</th>
							<th>盈利次数</th>
							<th>亏损次数</th>
							<th>持平次数</th>
							<th>交易盈亏</th>
							<th>盈亏百分比</th>
							<th>交易服务费</th>
							<th>接单返佣金额</th>
						</tr>
					</thead>
					<tbody>

                                <?php if (count($datas) == 0) { ?>
                                    <tr>
							<td colspan="11">暫時還沒有任何記錄</td>
						</tr>
								<?php } ?>                          
                                <?php foreach ($datas as $index=>$item) { ?>
                                <tr>
                            <td><?php echo ($index + 1)?></td>
                            <td><?php echo $sdate.' 至 '.$edate; ?></td>
							<td><?php echo $item->id_name; ?></td>
							
							<td><?php echo $item->volume; ?></td>
							<td><?php echo $item->total; ?></td>
							<td><?php echo $item->win_total; ?></td>
							<td><?php echo $item->total - $item->win_total -$item->draw_total; ?></td>
							<td><?php echo $item->draw_total; ?></td>
							<td><?php echo $item->win_amount- $item->lose_amount; ?></td>
							<td><?php echo ceil($item->win_total/$item->total *100); ?>%</td>
							<td><?php echo $item->fee_total; ?></td>
							
							<td></td>
						</tr>
                                <?php } ?>
                                
                            </tbody>

				</table>
			</div>
		</div>
	</div>
</div>



<?php include_once 'footer.php'; ?>
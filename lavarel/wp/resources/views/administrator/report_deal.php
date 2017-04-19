<?php include_once 'header.php'; ?>


<div id="page-wrapper">


	<div class="container-fluid">
		<div class="row" style="margin-top: 20px;">

			<div class="panel panel-default">
				<div class="panel-heading">查询条件</div>
				<div class="panel-body">
					
						<form class="form-inline">
							<div class="form-group">
								<label for="exampleInputName2">开始时间</label> 
								<input type="text" class="form-control" id="exampleInputName2" placeholder="Jane Doe">
							</div>
							<div class="form-group">
								<label for="exampleInputEmail2">结束时间</label> <input type="text"
									class="form-control" id="exampleInputEmail2"
									placeholder="jane.doe@example.com">
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
							<th>会员编号</th>
							<th>商品名称</th>
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

                                <?php foreach ($datas as $item) { ?>
                                <tr>
							<td><?php echo $item->id; ?></td>
							<td><?php echo $item->id_user; ?></td>
							<td><?php echo $item->agent_name; ?></td>
							<td><?php echo $item->cip; ?></td>
							<td><?php echo $item->created_at; ?></td>
							<td><?php echo $item->online; ?></td>
							<td><?php echo $item->from; ?></td>
						</tr>
                                <?php } ?>
                            </tbody>
					<tfoot>
						<tr>
							<td colspan="13" style="text-align: center;">
								<ul class="pagination">
									<li class="paginate_button previous"><a
										href="<?php echo $datas->previousPageUrl(); ?>">上一页</a></li>
									<li class="paginate_button active"><a href="#"><?php echo $datas->currentPage(); ?> / <?php echo $datas->lastPage(); ?>, 共 <?php echo $datas->total(); ?> 条记录</a></li>
									<li class="paginate_button next"><a
										href="<?php echo $datas->nextPageUrl(); ?>">下一页</a></li>
								</ul>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>



<?php include_once 'footer.php'; ?>
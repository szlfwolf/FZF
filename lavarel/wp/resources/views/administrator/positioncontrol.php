<?php include_once 'header.php'; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row" style="margin-top: 20px;">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>会员编号</th>
                                    <th>联系人</th>
                                    <th>联系电话</th>
                                    <th>保证金阀值</th>
                                    <th>当前余额</th>
                                    <th>当日盈亏</th>
                                    
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
                                    <td><?php echo $item->agent_name; ?></td>
                                    <td></td>
                                    <td><?php echo $item->body_phone; ?></td>
                                    <td></td>
                                    <td><?php echo $item->body_balance; ?></td>   
                                    <td></td>                                                                     
                                </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="13" style="text-align: center;">
                                        <ul class="pagination">
                                            <li class="paginate_button previous"><a href="<?php echo $datas->previousPageUrl(); ?>">上一页</a></li>
                                            <li class="paginate_button active"><a href="#"><?php echo $datas->currentPage(); ?> / <?php echo $datas->lastPage(); ?>, 共 <?php echo $datas->total(); ?> 条记录</a></li>
                                            <li class="paginate_button next"><a href="<?php echo $datas->nextPageUrl(); ?>">下一页</a></li>
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
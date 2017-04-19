<?php include_once 'header.php'; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row" style="margin-top: 20px;">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>登录账号</th>
                                    <th>用户姓名</th>
                                    <th>登录IP</th>
                                    <th>登录时间</th>
                                    <th>在线状态</th>
                                    <th>登录途径</th>
                                    
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
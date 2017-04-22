<?php include_once 'header.php'; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row" style="margin-top: 20px;">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>用户类型</th>
                                    <th>用户姓名</th>
                                    <th>登录时间</th>
                                    <th>联系电话</th>
                                    <th>登录途径</th>
                                    
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
                                    <td><?php 
                                    if( $item->type == 0) {echo '平台';} 
                                    else if( $item->type == 4) {echo '会员';}
                                    else if( $item->type == 1) {echo '机构';}
                                    else if( $item->type == 3) {echo '客户';}
                                    else {echo '非法类型:'.$item->type;} 
                                    ?></td>
                                    <td><?php echo $item->id_name; ?></td>
                                    <td><?php echo $item->updated_at; ?></td>
                                    <td><?php echo $item->body_phone; ?></td>
                                    <td><?php if(!empty($item->id_wechat)) {echo '微信端';} else {echo '后台';} ?></td>
                                    <!--                                    
                                    <td><?php echo $item->online; ?></td>
                                    <td><?php echo $item->from; ?></td>
                                     -->                                                                        
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
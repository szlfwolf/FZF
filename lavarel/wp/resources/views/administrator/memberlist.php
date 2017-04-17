<?php include_once 'header.php'; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <a class="btn btn-success" style="margin-top:20px;" href="/administrator/addMember" >添加会员</a>
                </div>
                <div class="row" style="margin-top: 20px;">

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>名字</th>
                                    <th>账号</th>
                                    <th>会员推广</th>
                                    <th>手机号码</th>
                                    <th>余额</th>
                                    <th>分红</th>
                                    <th>手续费</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if (count($datas) == 0) { ?>
                                    <tr>
                                        <td colspan="5">暫時還沒有任何記錄</td>
                                    </tr>
                                <?php } ?>

                                <?php foreach ($datas as $item) { ?>
                                <tr>
                                    <td><?php echo $item->id; ?></td>
                                     <td><?php echo $item->agent_name; ?></td>
                                    <td><?php echo $item->body_email; ?></td>
                                    <td><?php echo $item->agent_code; ?></td>
                                    <td><?php echo $item->body_phone	; ?></td>
                                    <td><?php echo $item->body_balance; ?></td>
                                    <td><?php echo $item->rate; ?>%</td>
                                    <td><?php echo $item->fee; ?>%</td>
                                    <!-- 
                                        | <a href="/administrator/records?id_user=<?php echo $item->id; ?>">资金记录</a> |
                                        | <a href="/administrator/payRequests?id_user=<?php echo $item->id; ?>">充值记录</a> | <a href="/administrator/withdrawRequests?id_user=<?php echo $item->id; ?>">提现记录</a>  |
                                    -->
                                    <td><a href="/administrator/update_member/<?php echo $item->id; ?>">修改资料</a> | <a href="/administrator/users?id_member=<?php echo $item->id; ?>">名下用户</a> |<a href="/administrator/updatePass_member/<?php echo $item->id; ?>">修改密码</a>  <a href="/administrator/payRequests/<?php echo $item->id; ?>">人工充值</a>| <a href="/administrator/users/<?php echo $item->id; ?>/withhold">人工扣款</a> | <a href="/administrator/delete_member/<?php echo $item->id; ?>">删除帐号</a></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" style="text-align: center;">
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
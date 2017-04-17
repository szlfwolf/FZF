<?php include_once 'header.php'; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row" style="margin-top: 40px;">
                    <div class="col-lg-12">
                        <form method="post" role="form">
                            <div class="form-group input-group">
                                <span class="input-group-addon">结余</span>
                                <input disabled="disabled" type="text" name="body_balance" class="form-control" value="<?php echo $user->body_balance; ?>">
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">姓名</span>
                                <input type="text" name="name" class="form-control" placeholder="银行开户名">
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">卡号</span>
                                <input type="number" name="number" class="form-control">
                            </div>

                            <div class="form-group input-group">
                                <span class="input-group-addon">银行</span>
                                 <select name="bank" class="weui_select bank_select">
                                    <option value="ccb">建设银行</option>
                                    <option value="icbc">工商银行</option>
                                    <option value="boc">中国银行</option>
                                    <option value="abc">农业银行</option>
                                    <option value="comm">交通银行</option>
                                    <option value="spdb">浦发银行</option>
                                    <option value="ecb">光大银行</option>
                                    <option value="cmbc">民生银行</option>
                                    <option value="cib">兴业银行</option>
                                    <option value="cmb">招商银行</option>
                                    <option value="psbc">邮政储蓄</option>
                                 </select>
                            </div>


                            <div class="form-group input-group">
                                <span class="input-group-addon">网点</span>
                                <input type="text" name="deposit" class="form-control" placeholder="银行开户网点名称">
                            </div>

                            <div class="form-group input-group">
                                <span class="input-group-addon">金额</span>
                                <input type="number" name="stake" class="form-control" placeholder="最低提现金额 100 元">
                            </div>
                            <div style="text-align: center">
                                <button type="submit" class="btn btn-primary">确认提现</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php include_once 'footer.php'; ?>
<?php include_once 'header.php'; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row" style="margin-top: 40px;">
                    <div class="col-lg-12">
                        <form method="post" role="form">
                            <div class="form-group input-group">
                                <span class="input-group-addon">会员名字</span>
                                <input  type="text" name="agent_name" class="form-control" >
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">会员账号</span>
                                <input type="text" name="body_email"  class="form-control">
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">会员密码</span>
                                <input type="text" name="body_password" class="form-control">
                            </div>

                            <div class="form-group input-group">
                                <span class="input-group-addon">会员推广码</span>
                                <input type="text" name="agent_code" class="form-control">
                            </div>

                            <div class="form-group input-group">
                                <span class="input-group-addon">手机号码</span>
                                <input type="tel" name="body_phone"  class="form-control">
                            </div>

                           

                            <div class="form-group input-group">
                                <span class="input-group-addon">分红比例</span>
                                <input type="number" name="rate"  class="form-control">
                                <span class="input-group-addon">%</span>
                            </div>
                             <div class="form-group input-group">
                                <span class="input-group-addon">手续费比例</span>
                                <input type="number" name="fee"  class="form-control">
                                <span class="input-group-addon">%</span>
                            </div>

                           


                            <div style="text-align: center">
                                <button type="submit" class="btn btn-primary">确认添加</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php include_once 'footer.php'; ?>
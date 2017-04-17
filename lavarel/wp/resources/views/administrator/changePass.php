<?php include_once 'header.php'; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row" style="margin-top: 40px;">
                    <div class="col-lg-12">
                        <form method="post" role="form">
                             
                            <div class="form-group input-group">
                                <span class="input-group-addon">原密码</span>
                                <input type="text" name="oldpass"  class="form-control" >
                            </div>
                          

                            <div class="form-group input-group">
                                <span class="input-group-addon">新密码</span>
                                <input type="tel" name="newpass"  class="form-control">
                            </div>
                         

                            <div style="text-align: center">
                                <button type="submit" class="btn btn-primary">确认修改</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php include_once 'footer.php'; ?>
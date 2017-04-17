<?php include_once 'header.php'; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row" style="margin-top: 40px;">
                    <div class="col-lg-12">
                        <form method="post" role="form">
                        <input  type="hidden" name="member_code" value="<?php echo $member_code; ?>"/>
                            <div class="form-group input-group">
                                <span class="input-group-addon">机构名字</span>
                                <input  type="text" name="agent_name" class="form-control" >
                            </div>
                            <div class="form-group input-group">
                            
                                <span class="input-group-addon">机构推广码</span>
                                 <span class="input-group-addon"><?php echo $member_code; ?></span>
                                <input type="text" name="agent_code" class="form-control" maxlength="3">
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">机构密码</span>
                                <input type="text" name="body_password" class="form-control">
                            </div>

                            

                            <div class="form-group input-group">
                                <span class="input-group-addon">手机号码</span>
                                <input type="tel" name="body_phone"  class="form-control">
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
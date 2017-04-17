<?php include_once 'header.php'; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row" style="margin-top: 40px;">
                    <div class="col-lg-12">
                        <form method="post" role="form">
                             <div class="form-group input-group">
                                <span class="input-group-addon">机构ID</span>
                                <input  type="text" disabled="disabled" name="show_id" class="form-control" value="<?php echo $agent->id; ?>" >
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">机构名字</span>
                                <input  type="text" name="agent_name"  disabled="disabled" class="form-control"  value="<?php echo $agent->agent_name; ?>">
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">机构账号</span>
                                <input type="email" name="body_email"   disabled="disabled" class="form-control" value="<?php echo $agent->body_email; ?>">
                            </div>
                          

                            <div class="form-group input-group">
                                <span class="input-group-addon">新密码</span>
                                <input type="tel" name="body_password"  class="form-control">
                            </div>
                            <input type="hidden" name="id" value="<?php echo $agent->id; ?>" />


                            <div style="text-align: center">
                                <button type="submit" class="btn btn-primary">确认添加</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php include_once 'footer.php'; ?>
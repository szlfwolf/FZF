<?php include_once 'header.php'; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row" style="margin-top: 40px;">
                    <div class="col-lg-12">
                        <form method="post" role="form">
                             <div class="form-group input-group">
                                <span class="input-group-addon">会员ID</span>
                                <input  type="text" disabled="disabled" name="show_id" class="form-control" value="<?php echo $agent->id; ?>" >
                            </div>

                             <div class="form-group input-group">
                                <span class="input-group-addon">会员账号</span>
                                <input type="email" name="body_email" disabled="disabled"  class="form-control" value="<?php echo $agent->body_email; ?>">
                            </div>
                          
                            <div class="form-group input-group">
                                <span class="input-group-addon">会员推广码</span>
                                <input type="text" name="agent_code" disabled="disabled"  class="form-control" value="<?php echo $agent->agent_code; ?>">
                            </div>


                            <div class="form-group input-group">
                                <span class="input-group-addon">会员名字</span>
                                <input  type="text" name="agent_name"  class="form-control"  value="<?php echo $agent->agent_name; ?>">
                            </div>
                           

                            <div class="form-group input-group">
                                <span class="input-group-addon">手机号码</span>
                                <input type="tel" name="body_phone"  class="form-control" value="<?php echo $agent->body_phone; ?>">
                            </div>

                
                            <div class="form-group input-group">
                                <span class="input-group-addon">分红比例</span>
                                <input type="number" name="rate"  class="form-control" value="<?php echo $agent->rate; ?>">
                                <span class="input-group-addon">%</span>
                            </div>

                            <div class="form-group input-group">
                                <span class="input-group-addon">手续费比例</span>
                                <input type="number" name="fee"  class="form-control" value="<?php echo $agent->fee; ?>">
                                <span class="input-group-addon">%</span>
                            </div>


                            <input type="hidden" name="id" value="<?php echo $agent->id; ?>" />
                           


                            <div style="text-align: center">
                                <button type="submit" class="btn btn-primary">确认更新</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php include_once 'footer.php'; ?>
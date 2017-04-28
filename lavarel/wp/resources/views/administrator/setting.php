<?php include_once 'header.php'; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row" style="margin-top: 40px;">
                    <div class="col-lg-12">
                        <form method="post" role="form">
                                                                 
                            <div class="form-group input-group">
                                <span class="input-group-addon">单用户但产品持仓单 单数限制</span>
                                <input  type="text" id="limit_num" name="limit_num" class="form-control" value="<?php echo $config->limit_num; ?>">
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">单用户但产品持仓单 金额限制</span>
                                <input  type="text" id="limit_amount" name="limit_amount" class="form-control" value="<?php echo $config->limit_amount; ?>">
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">60秒收益</span>
                                <input  type="text" id="return_rate_60" name="return_rate_60" class="form-control" value="<?php echo $config->return_rate_60; ?>">
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">180秒收益</span>
                                <input  type="text" id="return_rate_180" name="return_rate_180" class="form-control" value="<?php echo $config->return_rate_180; ?>">
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">300秒收益</span>
                                <input  type="text" id="return_rate_300" name="return_rate_300" class="form-control" value="<?php echo $config->return_rate_300; ?>">
                            </div>      
                            <div class="form-group input-group">
                                <span class="input-group-addon">600秒收益</span>
                                <input  type="text" id="return_rate_600" name="return_rate_600" class="form-control" value="<?php echo $config->return_rate_600; ?>">
                            </div>         
                            <div class="form-group input-group">
                                <span class="input-group-addon">1800秒收益</span>
                                <input  type="text" id="return_rate_1800" name="return_rate_1800" class="form-control" value="<?php echo $config->return_rate_1800; ?>">
                            </div>      
                            <div class="form-group input-group">
                                <span class="input-group-addon">3600秒收益</span>
                                <input  type="text" id="return_rate_3600" name="return_rate_3600" class="form-control" value="<?php echo $config->return_rate_3600; ?>">
                            </div>                                
                                                        
                            <div class="form-group input-group">
                                <span class="input-group-addon">休市起始时间</span>
                                <input  type="text" id="open_time" name="open_time" class="form-control" value="<?php echo $config->open_time; ?>">
                            </div>
                            <div class="form-group input-group">                            
                                <span class="input-group-addon">休市结束时间</span>                               
                                <input type="text" id="stop_time" name="stop_time" class="form-control" value="<?php echo $config->stop_time; ?>" >
                            </div>
                            <div style="text-align: center">
                                <button type="submit" class="btn btn-primary">确认</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<?php include_once 'footer.php'; ?>
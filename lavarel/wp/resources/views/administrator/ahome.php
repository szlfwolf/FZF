<?php include_once 'header.php'; ?>
<style>
    .list-group-item {
        border: 0;
        border-top: 1px solid #ddd;
    }
    .list-group-item:first-child {
        border-radius: 0;
        border: 0;
    }
</style>

        <div id="page-wrapper">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">数据总览</h1>
                    </div>
                </div>
                <div class="row">
                     <div class="col-lg-5 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-comments fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"><?php echo $user->body_balance;?></div>
                                        <div>余额</div>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>

                    <div class="col-lg-5 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-comments fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"><?php echo $user->agent_code;?></div>
                                        <div>推广码</div>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>

               
                
                
            </div>
        </div>
<?php include_once 'footer.php'; ?>
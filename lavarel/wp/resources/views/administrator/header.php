<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>管理后台</title>
    <link href="/public/resources/views/administrator/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/public/resources/views/administrator/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
    <link href="/public/resources/views/administrator/dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="/public/resources/views/administrator/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <script src="/public/resources/views/administrator/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="/public/resources/views/administrator/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/administrator">管理后台</a>
            </div>
             <?php if ($type == 0) { ?>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <a href="/administrator/users/export">
                        导出所有用户
                    </a>
                </li>
                <li>
                    <a href="/administrator/orders/export">
                        导出所有订单
                    </a>
                </li>
                <li>
                    <a href="/administrator/records/export">
                        导出所有资金
                    </a>
                </li>
                <li>
                    <a href="/administrator/payRequests/export">
                        导出所有充值
                    </a>
                </li>
                <li>
                    <a href="/administrator/withdrawRequests/export">
                        导出所有提现
                    </a>
                </li>
                <li>
                    <a href="/administrator/orderControl">
                        <?php if(env('ORDER_CONTROL')) echo '订单调控已开'; else echo '订单调控已关'; ?>
                    </a>
                </li>
                <li>
                    <a href="/administrator/orderWillWin">
                        <?php if(env('ORDER_WILL_WIN')) echo '强制盈利已开'; else echo '强制盈利已关'; ?>
                    </a>
                </li>
                <li>
                    <a href="/administrator/orderWillLost">
                        <?php if(env('ORDER_WILL_LOST')) echo '强制亏损已开'; else echo '强制亏损已关'; ?>
                    </a>
                </li>
            </ul>
             <?php } ?>

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <form method="get" action="/administrator/users">
                                <div class="input-group custom-search-form">
                                    <input name="body_phone" type="text" class="form-control" placeholder="根据手机号搜索用户...">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </li>
                        <li>
                            <a class="<?php echo ($active=='home')? 'active' : '';?>" href="/administrator"><i class="fa fa-dashboard fa-fw"></i> 总览</a>
                        </li>
                         <?php if ($type == 0) { ?>
                        
                        <li>
                                <a  class="<?php echo ($active=='agent')? 'active' : '';?>" href="#collapseOne" data-toggle="collapse" class="nav-header collapsed">
                                    <i class="fa fa-list fa-fw"></i>
                                    <span class="menu-text"> 用户管理 </span>
                                    <b class="fa fa-angle-down" style="float: right; line-height: 20px;"></b>
                                </a>
                                <ul id="collapseOne" class="nav nav-list collapse secondmenu" style="height: 0px;">

                                     <li>
                                        <a href="/administrator/memberlist"> <i class="fa fa-user fa-fw"></i> 会员列表 </a>
                                    </li>

                                     <li>
                                        <a href="/administrator/agentlist"> <i class="fa fa-user fa-fw"></i> 机构列表 </a>
                                    </li>
                                     <li>
									 <!--

                                      <a class="<?php echo ($active=='brokers')? 'active' : '';?>" href="/administrator/brokers"><i class="fa fa-user fa-fw"></i> 经纪人列表</a>
                                    </li>
									-->

                                    <li>
                                      <a class="<?php echo ($active=='users')? 'active' : '';?>" href="/administrator/users"><i class="fa fa-user fa-fw"></i> 客户列表</a>
                                    </li>

                                   
                                    <!--
                                    <li>
                                        <a href="/administrator/addAgent"> <i class="fa fa-plus fa-fw"></i> 添加机构 </a>
                                    </li>
                                    -->
                                   
                                </ul>
                        </li>
                        <?php } ?>

                        <?php if ($type == 4) { ?>
                        
                        <li>
                                <a  class="<?php echo ($active=='agent')? 'active' : '';?>" href="#collapseOne" data-toggle="collapse" class="nav-header collapsed">
                                    <i class="fa fa-list fa-fw"></i>
                                    <span class="menu-text"> 用户管理 </span>
                                    <b class="fa fa-angle-down" style="float: right; line-height: 20px;"></b>
                                </a>
                                <ul id="collapseOne" class="nav nav-list collapse secondmenu" style="height: 0px;">


                                     <li>
                                        <a href="/administrator/agentlist"> <i class="fa fa-user fa-fw"></i> 机构列表 </a>
                                    </li>
									<!--
                                     <li>
                                      <a class="<?php echo ($active=='brokers')? 'active' : '';?>" href="/administrator/brokers"><i class="fa fa-user fa-fw"></i> 经纪人列表</a>
                                    </li>
									-->

                                    <li>
                                      <a class="<?php echo ($active=='users')? 'active' : '';?>" href="/administrator/users"><i class="fa fa-user fa-fw"></i> 客户列表</a>
                                    </li>

                                   
                                    <!--
                                    <li>
                                        <a href="/administrator/addAgent"> <i class="fa fa-plus fa-fw"></i> 添加机构 </a>
                                    </li>
                                    -->
                                   
                                </ul>
                        </li>
                        <?php } ?>

                        <?php if ($type == 1) { ?>
                        
                        <li>
                                <a  class="<?php echo ($active=='agent')? 'active' : '';?>" href="#collapseOne" data-toggle="collapse" class="nav-header collapsed">
                                    <i class="fa fa-list fa-fw"></i>
                                    <span class="menu-text"> 用户管理 </span>
                                    <b class="fa fa-angle-down" style="float: right; line-height: 20px;"></b>
                                </a>
                                <ul id="collapseOne" class="nav nav-list collapse secondmenu" style="height: 0px;">
								<!--

                                     <li>
                                      <a class="<?php echo ($active=='brokers')? 'active' : '';?>" href="/administrator/brokers"><i class="fa fa-user fa-fw"></i> 经纪人列表</a>
                                    </li>
									-->

                                    <li>
                                      <a class="<?php echo ($active=='users')? 'active' : '';?>" href="/administrator/users"><i class="fa fa-user fa-fw"></i> 客户列表</a>
                                    </li>

                                   
                                    <!--
                                    <li>
                                        <a href="/administrator/addAgent"> <i class="fa fa-plus fa-fw"></i> 添加机构 </a>
                                    </li>
                                    -->
                                   
                                </ul>
                        </li>
                        <?php } ?>


                         <?php if ($type == 0 || $type == 4)  { ?>
                        <li>
                            <a class="<?php echo ($active=='orders')? 'active' : '';?>" href="/administrator/orders"><i class="fa fa-table fa-fw"></i> 订单</a>
                        </li>
                        <li>
                            <a class="<?php echo ($active=='records')? 'active' : '';?>" href="/administrator/records"><i class="fa fa-list-alt fa-fw"></i> 资金</a>
                        </li>
                        <li>
                            <a class="<?php echo ($active=='payRequests')? 'active' : '';?>" href="/administrator/payRequests"><i class="fa fa-rmb fa-fw"></i> 充值</a>
                        </li>
                        <?php } ?>


                        <?php if ($type == 0) { ?>
                        <li>
                            <a class="<?php echo ($active=='withdrawRequests')? 'active' : '';?>" href="/administrator/withdrawRequests"><i class="fa fa-briefcase fa-fw"></i> 提现</a>
                        </li>
                        <?php } ?>
                        <li>
                            <a class="<?php echo ($active=='objects')? 'active' : '';?>" href="/administrator/objects"><i class="fa fa-cloud fa-fw"></i> 标的</a>
                        </li>
                        <?php if ($type == 0) { ?>
                        <li>
                            <a class="<?php echo ($active=='feedbacks')? 'active' : '';?>" href="/administrator/feedbacks"><i class="fa fa-bug fa-fw"></i> 反馈</a>
                        </li>
                        <!--
                        <li >
                            <a class="<?php echo ($active=='administrators')? 'active' : '';?>" href="/administrator/administrators"><i class="fa fa-bug fa-fw"></i> 管理</a>
                        </li>
                      -->
                        <?php } ?>

                       <!--
                        <li >
                            <a class="<?php echo ($active=='agentWithDraw')? 'active' : '';?>" href="/administrator/agentWithdraw"><i class="fa fa-bug fa-fw"></i> 提现</a>
                        </li>
                         -->

                        <?php if ($type == 0) { ?>
                         <li>
                                <a  class="<?php echo ($active=='agent')? 'active' : '';?>" href="#collapseOrder" data-toggle="collapse" class="nav-header collapsed">
                                    <i class="fa fa-list fa-fw"></i>
                                    <span class="menu-text"> 统计 </span>
                                    <b class="fa fa-angle-down" style="float: right; line-height: 20px;"></b>
                                </a>
                                <ul id="collapseOrder" class="nav nav-list collapse secondmenu" style="height: 0px;">


                                     <li>
                                        <a href="/administrator/mem_orders_count"> <i class="fa fa-user fa-fw"></i> 会员报表 </a>
                                    </li>
                                    
                                    <li>
                                      <a class="<?php echo ($active=='users')? 'active' : '';?>" href="/administrator/ag_orders_count"><i class="fa fa-user fa-fw"></i> 机构报表</a>
                                    </li>
									<!--
                                    <li>
                                      <a class="<?php echo ($active=='users')? 'active' : '';?>" href="/administrator/br_orders_count"><i class="fa fa-user fa-fw"></i>经纪人报表</a>
                                    </li>
									-->
                                   
                                    <!--
                                    <li>
                                        <a href="/administrator/addAgent"> <i class="fa fa-plus fa-fw"></i> 添加机构 </a>
                                    </li>
                                    -->
                                   
                                </ul>
                        </li>
                          <?php } ?>

                          <?php if ($type == 4) { ?>
                         <li>
                                <a  class="<?php echo ($active=='agent')? 'active' : '';?>" href="#collapseOrder" data-toggle="collapse" class="nav-header collapsed">
                                    <i class="fa fa-list fa-fw"></i>
                                    <span class="menu-text"> 统计 </span>
                                    <b class="fa fa-angle-down" style="float: right; line-height: 20px;"></b>
                                </a>
                                <ul id="collapseOrder" class="nav nav-list collapse secondmenu" style="height: 0px;">


                                  
                                    <li>
                                      <a class="<?php echo ($active=='users')? 'active' : '';?>" href="/administrator/ag_orders_count"><i class="fa fa-user fa-fw"></i> 机构报表</a>
                                    </li>
									<!--
                                    <li>
                                      <a class="<?php echo ($active=='users')? 'active' : '';?>" href="/administrator/br_orders_count"><i class="fa fa-user fa-fw"></i>经纪人报表</a>
                                    </li>
									 -->
                                   
                                    <!--
                                    <li>
                                        <a href="/administrator/addAgent"> <i class="fa fa-plus fa-fw"></i> 添加机构 </a>
                                    </li>
                                    -->
                                   
                                </ul>
                        </li>
                          <?php } ?>


<?php if ($type == 1) { ?>
                         <li>
                                <a  class="<?php echo ($active=='agent')? 'active' : '';?>" href="#collapseOrder" data-toggle="collapse" class="nav-header collapsed">
                                    <i class="fa fa-list fa-fw"></i>
                                    <span class="menu-text"> 统计 </span>
                                    <b class="fa fa-angle-down" style="float: right; line-height: 20px;"></b>
                                </a>
                                <ul id="collapseOrder" class="nav nav-list collapse secondmenu" style="height: 0px;">


                                   <!--
                                    <li>
                                      <a class="<?php echo ($active=='users')? 'active' : '';?>" href="/administrator/br_orders_count"><i class="fa fa-user fa-fw"></i>经纪人报表</a>
                                    </li>
									 -->
                                   
                                    <!--
                                    <li>
                                        <a href="/administrator/addAgent"> <i class="fa fa-plus fa-fw"></i> 添加机构 </a>
                                    </li>
                                    -->
                                   
                                </ul>
                        </li>
                          <?php } ?>

                         <li>
                            <a href="/administrator/changePass"><i class="fa fa-sign-out fa-fw"></i> 修改密码</a>
                        </li>
                         <?php if ($type == 0) { ?>
						 <!--
                          <li>
                            <a href="/administrator/setting"><i class="fa fa-sign-out fa-fw"></i> 设置</a>
                        </li>
						-->
                            <?php } ?>

                        <li>
                            <a href="/administrator/signOut"><i class="fa fa-sign-out fa-fw"></i> 退出</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
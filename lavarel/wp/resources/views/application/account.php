<?php include_once 'header.php'; ?>

<style>
body{background:#000;}
body .navigator {
  border-top: 1px solid #272727;
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  overflow-x: auto;
  background: #1f1f1f;
}
body .navigator a {
  float: left;
  color: #7f8287;
  width: 25%;
  text-align: center;
  font-size: 14px;
  height: 46px;
  line-height: 46px;
}
body .navigator a.active {
  background: #2d2d2d;
  color: #fff;
}
body .navigator a:active {
  background: #252525;
  color: #eee;
}
</style>
<body>

    <div class="weui_cells" style="background:#1f1f1f; color:#fff">
        <div class="weui_cell">
            <div class="weui_cell_bd weui_cell_primary" >
                <p>账户余额</p>
            </div>
            <div class="weui_cell_ft" ><?php echo $user->body_balance; ?> CNY</div>
        </div>
    </div>

    <div class="weui_cells weui_cells_access">
        <a class="weui_cell" href="/account/pay" style="background:#1f1f1f; color:#fff">
            <div class="weui_cell_bd weui_cell_primary">
                <p>我要充值</p>
            </div>
            <div class="weui_cell_ft">
            </div>
        </a>
        <a class="weui_cell" href="/account/withdraw" style="background:#1f1f1f; color:#fff">
            <div class="weui_cell_bd weui_cell_primary">
                <p>我要提现</p>
            </div>
            <div class="weui_cell_ft">
            </div>
        </a>
        <a class="weui_cell" href="/account/records" style="background:#1f1f1f; color:#fff">
            <div class="weui_cell_bd weui_cell_primary">
                <p>资金记录</p>
            </div>
            <div class="weui_cell_ft">
            </div>
        </a>
        <a class="weui_cell" href="/account/orders" style="background:#1f1f1f; color:#fff">
            <div class="weui_cell_bd weui_cell_primary">
                <p>交易记录</p>
            </div>
            <div class="weui_cell_ft">
            </div>
        </a>
    </div>

   

	    <div class="navigator clearfix">
        <a class="" href="/objects" class="active">商品价格</a>
        <a class="" href="/orders/hold">在手订单</a>
        <a class="" href="/orders/history">历史订单</a>
        <a class="active" href="/account">我的账户</a>
    </div>

<?php include_once 'footer.php'; ?>

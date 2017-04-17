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
                <p>user balance</p>
            </div>
            <div class="weui_cell_ft" ><?php echo $user->body_balance; ?> CNY</div>
        </div>
    </div>

    <div class="weui_cells weui_cells_access">
        <a class="weui_cell" href="/account/pay" style="background:#1f1f1f; color:#fff">
            <div class="weui_cell_bd weui_cell_primary">
                <p>Top up my account</p>
            </div>
            <div class="weui_cell_ft">
            </div>
        </a>
        <a class="weui_cell" href="/account/withdraw" style="background:#1f1f1f; color:#fff">
            <div class="weui_cell_bd weui_cell_primary">
                <p>Withdraw from my account</p>
            </div>
            <div class="weui_cell_ft">
            </div>
        </a>
        <a class="weui_cell" href="/account/records" style="background:#1f1f1f; color:#fff">
            <div class="weui_cell_bd weui_cell_primary">
                <p>capital record</p>
            </div>
            <div class="weui_cell_ft">
            </div>
        </a>
        <a class="weui_cell" href="/account/orders" style="background:#1f1f1f; color:#fff">
            <div class="weui_cell_bd weui_cell_primary">
                <p>trade record</p>
            </div>
            <div class="weui_cell_ft">
            </div>
        </a>
    </div>

    <div class="weui_cells weui_cells_access">
        <a class="weui_cell" href="/account/expand/<?php echo $user->id; ?>" style="background:#1f1f1f; color:#fff">
            <div class="weui_cell_bd weui_cell_primary">
                <p>My promotion of two-dimensional code</p>
            </div>
            <div class="weui_cell_ft"></div>
        </a>
    </div>

    <div class="weui_cells">
        <div class="weui_cell" style="background:#1f1f1f; color:#fff">
            <div class="weui_cell_bd weui_cell_primary" >
                <p>Friends under my account</p>
            </div>
            <div class="weui_cell_ft"><?php echo $count_refers; ?>  </div>
        </div>
        <div class="weui_cell" style="background:#1f1f1f; color:#fff">
            <div class="weui_cell_bd weui_cell_primary">
                <p>My "sharing profit"</p>
            </div>
            <div class="weui_cell_ft"><?php echo $count_bonus; ?> CNY</div>
        </div>
    </div>

	    <div class="navigator clearfix">
        <a class="" href="/objects" class="active">Home</a>
        <a class="" href="/orders/hold">Hold</a>
        <a class="" href="/orders/history">Order</a>
        <a class="active" href="/account">My account</a>
    </div>

<?php include_once 'footer.php'; ?>

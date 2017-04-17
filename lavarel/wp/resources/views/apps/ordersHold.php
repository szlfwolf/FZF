<?php include_once 'header.php'; ?>

<div class="head clearfix">
    <div class="left">
        <div class="wrapper">
            <i>用户</i>
            <span class="user_body_phone"><?php echo($user->body_phone); ?></span>
        </div>
    </div>
    <div class="right">
        <div class="wrapper">
            <i>可用余额</i>
            <span class="user_body_balance"><?php echo($user->body_balance); ?> CNY</span>
        </div>
    </div>
</div>

<div class="container orders">
    <table class="orders">
        <thead>
            <tr>
                <td width="50%">商品</td>
                <td width="25%">额度</td>
                <td width="25%">盈亏</td>
            </tr>
        </thead>
        <tbody>
<?php foreach ($orders as $item) { ?>
            <tr data-seconds="<?php
                echo (strtotime($item->created_at) + $item->body_time) - time();                      
            ?>" data-object-id="<?php echo $item->object->id; ?>" data-direction="<?php echo $item->body_direction; ?>" data-id="<?php echo $item->id; ?>" data-price-buying="<?php echo $item->body_price_buying; ?>" class="clearLine">
                <td><?php echo $item->object->body_name; ?> 
                    <span style="color: <?php echo $item->body_direction ? '#ed0000' : '#00ff0a'; ?>;"><?php echo $item->body_direction ? '看涨' : '看跌'; ?></span>
                </td>
                <td ><span id="currentnumber"><?php echo intval($item->body_stake); ?></span></td>
                <td  > <span id="yingkui"></span>  </td>
            </tr>
            <tr>
                <td colspan="3">
                    <p>时间: <?php echo date('Y-m-d H:i:s', strtotime($item->created_at)); ?>&nbsp;&nbsp;周期:<span id="cicle"><?php
 if($item->body_time == 60) echo '1M';
 if($item->body_time == 300) echo '5M';
 if($item->body_time == 900) echo '15M';
 if($item->body_time == 1800) echo '30M';
 if($item->body_time == 3600) echo '1H';
 ?></span>&nbsp;&nbsp;收益率:<span id="return_rate"> <?php
                        if($item->body_time == 60) echo '75%';
                        if($item->body_time == 300) echo '77%';
                        if($item->body_time == 900) echo '80%';
                        if($item->body_time == 1800) echo '82%';
                        if($item->body_time == 3600) echo '85%';
                        ?> </span></p>
                    <p>开仓价格: <?php echo(sprintf('%.' . $item->object->body_price_decimal . 'f', $item->body_price_buying)); ?>&nbsp;&nbsp;当前价格: <span class="price_now"><?php echo(sprintf('%.' . $item->object->body_price_decimal . 'f', $item->object->body_price)); ?></span></p>
               <p style="color:red">倒计时:<span id="daojishi" ><?php echo (strtotime($item->created_at) + $item->body_time) - time(); ?></span>秒</p>
                </td>

            </tr>
<?php } ?>
        </tbody>
    </table>
</div>

<?php include_once 'footer.php'; ?>


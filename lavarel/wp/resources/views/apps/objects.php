<?php include_once 'header.php'; ?>


<div class="head clearfix">
    <div class="left "style="width:25%">
        <div class="wrapper">
            <i>用户</i>
            <span class="user_body_phone"><?php echo($user->body_phone); ?></span>
        </div>
    </div>   
    
    <div class="left clearfix"style="width:25%">
        <div class="wrapper">            
          <a href="/account/pay" style="color:#7f8287">  <span class="user_body_phone" style="font-size:18px;">充值</span></a>
        </div>
       </div>
    
         <div class="left clearfix"style="width:25%">
        <div class="wrapper">            
          <a href="/account/withdraw" style="color:#7f8287">  <span class="user_body_phone" style="font-size:18px">提现</span></a>
        </div>
    </div>
    <div class="right clearfix"style="width:20%;">    
        <div class="wrapper">
            <i>可用余额</i>
            <span class="user_body_balance"><?php echo($user->body_balance); ?> CNY</span>
        </div>
    </div>
</div>


<div class="container dashboard">
    <table class="dashboard">
        <thead>
            <tr>
                <td width="50%">商品</td>
                <td>买入</td>
                <td>卖出</td>
            </tr>
        </thead>
        <tbody>
<?php foreach ($objects as $item) { ?>
            <tr data-id="<?php echo($item->id); ?>">
                <td><?php echo($item->body_name); ?> </td>
                <td class="price <?php
                    if($item->body_price_previous > $item->body_price) echo 'green';
                    else echo 'red';  
                ?>"><?php echo(sprintf('%.' . $item->body_price_decimal . 'f', $item->body_price)); ?></td>
                <td class="price <?php
                    if($item->body_price_previous > $item->body_price) echo 'green';
                    else echo 'red';  
                ?>"><?php echo(sprintf('%.' . $item->body_price_decimal . 'f', $item->body_price)); ?></td>
            </tr>
<?php } ?>
        </tbody>
    </table>
</div>

<?php include_once 'footer.php'; ?>

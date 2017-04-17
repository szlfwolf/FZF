<?php include_once 'header.php'; ?>

<div class="head clearfix">
    <div class="left">
        <div class="wrapper">
            <i>user</i>
            <span class="user_body_phone"><?php echo($user->body_phone); ?></span>
        </div>
    </div>
    <div class="right">
        <div class="wrapper">
            <i>user balance</i>
            <span class="user_body_balance"><?php echo($user->body_balance); ?> CNY</span>
        </div>
    </div>
</div>

<div class="container orders">
    <table class="orders">
        <thead>
            <tr>
                <td width="50%">goods</td>
                <td>amount</td>
                <td>loss or gain</td>
            </tr>
        </thead>
        <tbody>
<?php foreach ($orders as $item) { ?>
            <tr data-id="<?php echo $item->id; ?>" class="clearLine">
                <td><?php echo $item->object->body_name_english; ?>
                    <span style="color: <?php echo $item->body_direction ? '#ed0000' : '#00ff0a'; ?>;"><?php echo $item->body_direction ? 'Call' : 'Bearish'; ?></span>
                </td>
                <td><?php echo intval($item->body_stake); ?></td>
                <td class="price <?php 
                    if ($item->body_is_win == 1) echo 'red';
                    else if ($item->body_is_draw == 1) echo '';
                    else echo 'green';
                ?>">
                    <?php
                    if ($item->body_is_win == 1) echo '';
                    else if ($item->body_is_draw == 1) echo '';
                    else echo '-';
                    ?>

                    <?php
                    if ($item->body_is_win == 1) echo $item->body_bonus;
                    else if ($item->body_is_draw == 1) echo '0';
                    else echo $item->body_stake;
                ?></td>
            </tr>
            <tr data-id="<?php echo $item->id; ?>">
                <td colspan="3"><p>time: <?php echo date('Y-m-d H:i:s', strtotime($item->created_at)); ?>&nbsp;&nbsp;cicle: <?php
 if($item->body_time == 60) echo '1M';
 if($item->body_time == 300) echo '5M';
 if($item->body_time == 900) echo '15M';
 if($item->body_time == 1800) echo '30M';
 if($item->body_time == 3600) echo '1H';
 ?></p></td>
            </tr>
<?php } ?>
        </tbody>
        <?php if($orders->hasMorePages()) { ?>
        <tfoot>
            <tr>
                <td colspan="3">
                    <a href="<?php echo $orders->nextPageUrl();?>">next</a>
                </td>
            </tr>
        </tfoot>
        <?php } ?>
    </table>
</div>

<?php include_once 'footer.php'; ?>

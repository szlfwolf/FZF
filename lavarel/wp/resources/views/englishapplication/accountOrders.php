<?php include_once 'header.php'; ?>

<body data-controller="accountOrdersController">

    <table border="0">
        <tbody>
        <?php if (count($orders) == 0) { ?>
            <tr>
                <td>There is no record for the time being</td>
            </tr>
        <?php } ?>
        <?php foreach ($orders as $order) { 
                $object = $order->object;
                $is_striked = 1;
                if ($order->striked_at == '0000-00-00 00:00:00') $is_striked = 0;
        ?>
            <tr class="orderDetail" style="border-left: 4px solid <?php
                if ($is_striked) {
                    if ($order->body_is_win == 1) echo '#F43530';
                    else if ($order->body_is_draw == 1) echo '#D0B628';
                    else echo '#04BE02';
                } else {
                    echo '#D0B628';
                }                                   
            ?>">
                <td>
                    <strong><span style="color: <?php
                if ($is_striked) {
                    if ($order->body_is_win == 1) echo '#F43530';
                    else if ($order->body_is_draw == 1) echo '#D0B628';
                    else echo '#04BE02';
                } else {
                    echo '#D0B628';
                }                                   
            ?>"><?php
                if ($is_striked) {
                    if ($order->body_is_win == 1) echo 'profit';
                    else if ($order->body_is_draw == 1) echo 'dogfall';
                    else echo 'loss';
                } else {
                    echo 'wate a moment';
                }      

            ?></span> <?php echo $object->body_name_english; ?> <?php echo $order->body_time;?> s</strong>
                    <p>In <?php echo date('Y-m-d H:i:s', strtotime($order->created_at)); ?> with <?php echo $order->body_price_buying; ?> purchase <?php echo $order->body_stake;?> rmb <?php echo $order->body_direction ? 'Call' : 'bearish'; ?></p>
                    <?php if ($is_striked) { ?>
                    <p>In <?php echo date('Y-m-d H:i:s', strtotime($order->created_at) + intval($order->body_time)); ?> with <?php echo $order->body_price_striked; ?> Settlement</p>
                    <?php } else { ?>
                    <p>In <?php echo date('Y-m-d H:i:s', strtotime($order->created_at) + intval($order->body_time)); ?> Settlement</p>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
        <?php if($orders->hasMorePages()) { ?>
        <tfoot>
            <tr>
                <td>
                    <a href="<?php echo $orders->nextPageUrl();?>" class="weui_btn weui_btn_primary" style="margin: 20px auto; font-size: 14px; width: 200px;">next</a>
                </td>
            </tr>
        </tfoot>
        <?php } ?>
    </table>

<?php include_once 'footer.php'; ?>

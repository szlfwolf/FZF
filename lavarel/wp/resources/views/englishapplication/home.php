<?php include_once 'header.php'; ?>
<script src="/public/statics/libs/highstock/highstock.js"></script>
<body data-controller="appController" class="app">

    <div id="loading">
        Load, please later
    </div>

    <div id="workspace" style="visibility: hidden;">
        <div id="stack">
            <div class="left">
                <div class="wrapper">
                    <div class="title">Investment amount</div>
                    <div class="buttons">
                        <a id="stake_minus" class="button_tap action_button minus ion-android-remove"></a>
                        <span class="button_number">￥<i id="order_stake">--</i></span>
                        <a id="stake_plus" class="button_tap action_button plus ion-android-add"></a>
                    </div>
                    <div class="desc">
                        <span class="font"><i class="ion-thumbsup"></i>Expected return</span>
                        <span class="number">￥<i id="order_profit">--</i></span>
                    </div>
                </div>
            </div>
            <div class="right">
                <div class="wrapper">
                    <div class="title">expiration time</div>
                    <div class="buttons">
                        <a id="time_minus" class="button_tap action_button minus ion-android-remove"></a>
                        <span class="button_number"><i id="order_time">--</i> s</span>
                        <a id="time_plus" class="button_tap action_button plus ion-android-add"></a>
                    </div>
                    <div class="desc">
                        <span class="font"><i class="ion-locked"></i>Protected amount</span>
                        <span class="number">￥0.00</span>
                    </div>
                </div>
            </div>
        </div>
        <div id="object">
            <label for="select_objects" class="button_tap">
                <div class="left">
                    <div class="wrapper">
                        <span class="title">Assets <i id="object_name">--</i></span>
                    </div>
                </div>
                <div class="right">
                    <div class="wrapper">
                        <span class="title">Payment amount <i id="object_profit">--</i></span>
                    </div>
                </div>
            </label>
            <select id="select_objects">
<?php 
        $firstTag = FALSE;
        foreach ($objects as $object) { 
?>
                <option value="<?php echo $object->id; ?>"<?php 
                    if(!$firstTag){
                        $firstTag = TRUE;
                        echo ' selected="selected"';
                    }
                ?>><?php echo $object->body_name; ?></option>
<?php } ?>
            </select>
        </div>
        <div id="objectArrow">Selection of trading varieties</div>
        <div id="liveChart" style="width: 100%; height: 260px;"></div>
        <div id="liveChartButton"></div>
        <div id="actions">
            <div class="left">
                <div class="wrapper">
                    <a id="order_up" class="button_tap action_button up"><span class="ion-arrow-up-b"></span>
                        Call</a>
                </div>
            </div>
            <div class="center">
                <div class="wrapper">
                    <span id="account_balance" class="button_tap balance">surplus ￥<?php echo floatval($balance); ?></span>
                </div>
            </div>
            <div class="right">
                <div class="wrapper">
                    <a id="order_down" class="button_tap action_button down"><span class="ion-arrow-down-b"></span>Bearish</a>
                </div>
            </div>
        </div>
    </div>

<?php include_once 'footer.php'; ?>

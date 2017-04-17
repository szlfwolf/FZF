<?php include_once 'header.php'; ?>

<body data-controller="accountPayController">
<form method="post">
    <input id="input_stake" type="hidden" name="stake" value="100" />
    <table class="stacksTable">
        <tr>
            <td><a data-stake="100" class="button_tap weui_btn weui_btn_plain_primary">￥100</a></td>
            <td><a data-stake="200" class="button_tap weui_btn weui_btn_plain_default">￥200</a></td>
            <td><a data-stake="300" class="button_tap weui_btn weui_btn_plain_default">￥300</a></td>
        </tr>
        <tr>
            <td><a data-stake="1000" class="button_tap weui_btn weui_btn_plain_default">￥1000</a></td>
            <td><a data-stake="3000" class="button_tap weui_btn weui_btn_plain_default">￥3000</a></td>
            <td><a data-stake="5000" class="button_tap weui_btn weui_btn_plain_default">￥5000</a></td>
        </tr>
    </table>
    <div class="weui_cells_title">Please select recharge channels</div>
    <div class="weui_cells weui_cells_radio">
        <label class="weui_cell weui_check_label" for="online">
            <div class="weui_cell_bd weui_cell_primary">
                <p>Online payment</p>
            </div>
            <div class="weui_cell_ft">
                <input type="radio" value="online" checked="checked" class="weui_check" name="gateway" id="online">
                <span class="weui_icon_checked"></span>
            </div>
        </label>
        <label class="weui_cell weui_check_label" for="staff">
            <div class="weui_cell_bd weui_cell_primary">
                <p>Artificial recharge</p>
            </div>
            <div class="weui_cell_ft">
                <input type="radio" value="staff" class="weui_check" name="gateway" id="staff">
                <span class="weui_icon_checked"></span>
            </div>
        </label>
    </div>

    <div class="weui_btn_area">
        <a href="javascript:app.instance.controller.clickedSubmit();" class="weui_btn weui_btn_primary">Confirm</a>
    </div>

</form>
<?php include_once 'footer.php'; ?>

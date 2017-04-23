<?php include_once 'header.php'; ?>

<body data-controller="accountPayController">
<form method="post">
    <input id="input_stake" type="hidden" name="stake" value="100" />
    <table class="stacksTable">
        <tr>
            <td><a data-stake="1" class="button_tap weui_btn weui_btn_plain_primary">1 元</a></td>
            <td><a data-stake="300" class="button_tap weui_btn weui_btn_plain_default">300 元</a></td>
            <td><a data-stake="500" class="button_tap weui_btn weui_btn_plain_default">500 元</a></td>
        </tr>
        <tr>
            <td><a data-stake="1000" class="button_tap weui_btn weui_btn_plain_default">1000 元</a></td>
            <td><a data-stake="3000" class="button_tap weui_btn weui_btn_plain_default">3000 元</a></td>
            <td><a data-stake="5000" class="button_tap weui_btn weui_btn_plain_default">5000 元</a></td>
        </tr>
    </table>
    <div class="weui_cells_title">请选择充值渠道</div>
    <div class="weui_cells weui_cells_radio">

        <label class="weui_cell weui_check_label" for="weixin" >
            <div class="weui_cell_bd weui_cell_primary">
                <p>微信支付</p>
            </div>
            <div class="weui_cell_ft">
                <input type="radio" value="weixin"  checked="checked"  class="weui_check" name="gateway" id="weixin">
                <span class="weui_icon_checked"></span>
            </div>
        </label>


        <label class="weui_cell weui_check_label" for="staff" >
            <div class="weui_cell_bd weui_cell_primary">
                <p>扫码支付</p>
            </div>
            <div class="weui_cell_ft">
                <input type="radio" value="staff1"    class="weui_check" name="gateway" id="staff">
                <span class="weui_icon_checked"></span>
            </div>
        </label>


        <label class="weui_cell weui_check_label" for="online">
            <div class="weui_cell_bd weui_cell_primary">
                <p>快捷支付</p>
            </div>
            <div class="weui_cell_ft">
                <input type="radio" value="online" class="weui_check" name="gateway" id="online">
                <span class="weui_icon_checked"></span>
            </div>
        </label>

        <label class="weui_cell weui_check_label" for="online">
            <input type="text" name="kahao" value="" placeholder="请输入银行卡号" style="width: 100%;height: 2rem;line-height: 2rem;" >
        </label>
       
    </div>

    <div class="weui_btn_area">
        <a href="javascript:app.instance.controller.clickedSubmit();" class="weui_btn weui_btn_primary">确认</a>
    </div>

</form>
<?php include_once 'footer.php'; ?>

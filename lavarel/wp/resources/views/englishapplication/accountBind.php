<?php include_once 'header.php'; ?>

<body data-controller="accountBindController">
<form method="post">

    <div class="weui_cells_title">Please bind your mobile phone</div>
    <div class="weui_cells weui_cells_form">
        <div class="weui_cell weui_vcode">
            <div class="weui_cell_hd"><label class="weui_label" style="width: 5em;">mobile phone</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input name="mobile" class="weui_input" type="number" placeholder="Your mobile phone">
            </div>
            <div class="weui_cell_ft">
                <a id="sendSMS" style="margin: 8px 10px;" class="button_tap weui_btn weui_btn_mini weui_btn_default">Send verification SMS</a>
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label" style="width: 5em;">SMS verification</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input name="vcode" class="weui_input" type="number" placeholder="SMS verification code you received">
            </div>
        </div>



        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label" style="width: 5em;">Invite Code</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input name="id_agent" class="weui_input" type="number" placeholder="Invite Code">
            </div>
        </div>




    </div>

    <div class="weui_cells_title">Click to confirm that you are committed to</div>
    <div class="weui_cells">
        <div class="weui_cell">
            <div class="weui_cell_bd weui_cell_primary">
                <p>You have reached the age of 18</p>
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_bd weui_cell_primary">
                <p>You have learned that any investment has a risk,you will be treated with caution.</p>
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_bd weui_cell_primary">
                <p>You will comply with the rules and regulations proposed by the Yangtze River micro trading operations center.</p>
            </div>
        </div>
    </div>

    <div class="weui_btn_area">
        <a href="javascript:app.instance.controller.clickedSubmit();" class="weui_btn weui_btn_primary">confirm</a>
    </div>

</form>

<?php include_once 'footer.php'; ?>

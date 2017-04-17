<?php include_once 'header.php'; ?>

<body>
<form method="post">

    <div class="weui_cells weui_cells_access">
        <a class="weui_cell" href="/account/records">
            <div class="weui_cell_bd weui_cell_primary">
                <p>Current balance</p>
            </div>
            <div class="weui_cell_ft"><?php echo $user->body_balance; ?> CNY</div>
        </a>
        <a class="weui_cell" href="/account/withdraw/records">
            <div class="weui_cell_bd weui_cell_primary">
                <p>Present record</p>
            </div>
            <div class="weui_cell_ft">
            </div>
        </a>
    </div>

    <div class="weui_cells weui_cells_form">
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">Full name</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input name="name" class="weui_input" type="text" placeholder="Bank account name">
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">Credit Card Number</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input name="number" class="weui_input" type="number" placeholder="Bank card number">
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">Bank</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <select name="bank" class="weui_select bank_select">
                    <option value="ccb">CCB</option>
                    <option value="icbc">ICBC</option>
                    <option value="boc">BOC</option>
                    <option value="abc">ABC</option>
                    <option value="comm">COMM</option>
                    <option value="spdb">SPDB</option>
                    <option value="ecb">ECB</option>
                    <option value="cmbc">EMBC</option>
                    <option value="cib">CIB</option>
                    <option value="cmb">CMB</option>
                    <option value="psbc">PSBC</option>
                </select>
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">branch</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input name="deposit" class="weui_input" type="text" placeholder="Bank account name">
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">amount</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input name="stake" class="weui_input" type="number" placeholder="Minimum cash withdrawal amount 100 yuan">
            </div>
        </div>
    </div>

    <div class="weui_btn_area">
        <a href="javascript:document.getElementsByTagName('form')[0].submit();" class="weui_btn weui_btn_primary">confirm</a>
    </div>

</form>

<?php include_once 'footer.php'; ?>

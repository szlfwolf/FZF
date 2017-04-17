<?php include_once 'header.php'; ?>

<body>
<form method="post">

    <div class="weui_cells_title">Please describe your problem.</div>
    <div class="weui_cells weui_cells_form">
        <div class="weui_cell">
            <div class="weui_cell_bd weui_cell_primary">
                <textarea name="content" class="weui_textarea" rows="5"></textarea>
            </div>
        </div>
    </div>

    <div class="weui_cells_title">Contact means (for feedback to you)</div>

    <div class="weui_cells">
        <div class="weui_cell weui_cell_select weui_select_before">
            <div class="weui_cell_hd">
                <select class="weui_select" name="tool">
                    <option value="QQ">QQ</option>
                    <option value="WECHAT">WeChat</option>
                    <option value="MOBILE">Mobile phone</option>
                </select>
            </div>
            <div class="weui_cell_bd weui_cell_primary">
                <input name="number" class="weui_input" type="text" placeholder="Please enter a number">
            </div>
        </div>
    </div>

    <div class="weui_btn_area">
        <a href="javascript:document.getElementsByTagName('form')[0].submit();" class="weui_btn weui_btn_primary">Confirm</a>
    </div>

</form>
<?php include_once 'footer.php'; ?>

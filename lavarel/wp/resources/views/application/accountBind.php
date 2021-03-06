<?php include_once 'header.php'; ?>

<body data-controller="accountBindController">
<form method="post">

    <div class="weui_cells_title">请绑定你的手机</div>
    <div class="weui_cells weui_cells_form">
        <div class="weui_cell weui_vcode">
            <div class="weui_cell_hd"><label class="weui_label" style="width: 5em;">手机号码</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input name="mobile" class="weui_input" type="number" placeholder="您的手机号码">
            </div>
            <div class="weui_cell_ft">
                <a id="sendSMS" style="margin: 8px 10px;" class="button_tap weui_btn weui_btn_mini weui_btn_default">发送验证短信</a>
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label" style="width: 5em;">短信验证</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input name="vcode" class="weui_input" type="number" placeholder="您收到的短信验证码">
            </div>
        </div>
		<div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label" style="width: 5em;">姓名</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input name="id_name" class="weui_input" type="text" placeholder="请输入姓名">
            </div>
        </div>
        

        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label" style="width: 5em;">推广码</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input name="id_agent" class="weui_input" type="number" placeholder="请输入机构编码">
            </div>
        </div>



         
    </div>

    <div class="weui_cells_title">点击确认则代表你承诺</div>
    <div class="weui_cells">
        <div class="weui_cell">
            <div class="weui_cell_bd weui_cell_primary">
                <p>您已经年满 18 岁。</p>
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_bd weui_cell_primary">
                <p>您已经了解任何投资均有风险，你会谨慎对待。</p>
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_bd weui_cell_primary">
                <p>您会遵守新华云商城所提出的各項规则、规定。</p>
            </div>
        </div>
    </div>

    <div class="weui_btn_area">
        <a href="javascript:app.instance.controller.clickedSubmit();" class="weui_btn weui_btn_primary">确认</a>
    </div>

</form>

<?php include_once 'footer.php'; ?>

<?php include_once 'header.php'; ?>
<style>
/*弹出层插件样式开始*/
.Prompt_floatBoxBg{display:none;width:100%;height:100%;background:#000;position:fixed !important;/*ie7 ff*/position:absolute;top:0;left:0;filter:alpha(opacity=0);opacity:0; z-index:999;}
.Prompt_floatBox{
z-index:1000;
display: block;
position: absolute;
padding:6px;
text-align:center;
top: 404.5px;
left: 531.5px;
height: auto;
z-index: 10000;
word-wrap: break-word;
-webkit-box-shadow: rgba(0, 0, 0, 0.498039) 0px 0px 15px;
box-shadow: rgba(0, 0, 0, 0.498039) 0px 0px 15px;
border-top-left-radius: 6px;
border-top-right-radius: 6px;
border-bottom-right-radius: 6px;
border-bottom-left-radius: 6px;
background-color: white;
opacity: 1;
}
.Prompt_floatBox .content{padding:10px;background:#fff;overflow-x:hidden;overflow-y: auto;}
/*弹出层插件样式结束*/
</style>

<script src="/public/statics_v2/js/libs/highstock/highstock.js"></script>
<div class="container objectsDetail">
    <table data-name="<?php echo $item->body_name; ?>" data-id="<?php echo $item->id; ?>" data-period="<?php echo $period; ?>" class="objectsDetail">
        <thead>
            <tr>
                <td colspan="4" width="50%">goods</td>
                <td colspan="2">purchase</td>
                <td colspan="2">sell out</td>
            </tr>
        </thead>
        <tbody>
            <tr data-id="<?php echo $item->id; ?>" class="clearLine">
                <td colspan="4"><?php echo $item->body_name; ?> <?php echo($item->body_name_english); ?></td>
                <td colspan="2" class="price <?php
                    if($item->body_price_previous > $item->body_price) echo 'green';
                    else echo 'red';  
                ?>"><?php echo(sprintf('%.' . $item->body_price_decimal . 'f', $item->body_price)); ?></td>
                <td colspan="2" class="price <?php
                    if($item->body_price_previous > $item->body_price) echo 'green';
                    else echo 'red';  
                ?>"><?php echo(sprintf('%.' . $item->body_price_decimal . 'f', $item->body_price)); ?></td>
            </tr>
            <tr data-id="<?php echo $item->id; ?>">
                <td colspan="8"><p>update time: <span class="updateTime"><?php echo date('Y-m-d H:i:s', strtotime($item->updated_at)); ?></span></p></td>
            </tr>
            <tr>
                <td colspan="3">
                    <div id="stakeSelector" class="selector">
                        <label for="select_stake">trading volume</label>
                        <input readonly="readonly" id="select_stake" type="number" value="50">
                        <ul style="display: none;">
                             
                            <li><a href="javascript:$('#select_stake').val(50); $('#stakeSelector ul').hide();">50</a></li>
                            <li><a href="javascript:$('#select_stake').val(100); $('#stakeSelector ul').hide();">100</a></li>
                            <li><a href="javascript:$('#select_stake').val(200); $('#stakeSelector ul').hide();">200</a></li>
                            <li><a href="javascript:$('#select_stake').val(500); $('#stakeSelector ul').hide();">500</a></li>
                            <li><a href="javascript:$('#select_stake').val(1000); $('#stakeSelector ul').hide();">1000</a></li>
                            <li><a href="javascript:$('#select_stake').val(2000); $('#stakeSelector ul').hide();">2000</a></li>
							<li><a href="javascript:$('#select_stake').val(5000); $('#stakeSelector ul').hide();">5000</a></li>
                        </ul>
                    </div>
                </td>
                <td colspan="2">
                    <div id="stakeSelector" class="selector">
                        <label for="select_stake">return rate</label>
                        <input readonly="readonly"   type="text" value="75%" id="returnrate">

                    </div>
                </td>
                <td colspan="3">
                    <div id="timeSelector" class="selector">
                        <label for="select_time">cycle</label>
                        <input readonly="readonly" id="select_time" type="text" value="1M">
                        <ul style="display: none;">
                            <li><a href="javascript:$('#select_time').val('1M');$('#returnrate').attr('value','75%');$('#content_rate').html('70%'); $('#timeSelector ul').hide();">1M</a></li>
                            <li><a href="javascript:$('#select_time').val('5M');$('#returnrate').attr('value','77%'); $('#content_rate').html('73%');$('#timeSelector ul').hide();">5M</a></li>
                            <li><a href="javascript:$('#select_time').val('15M'); $('#returnrate').attr('value','80%');$('#content_rate').html('75%');$('#timeSelector ul').hide();">15M</a></li>
                            <li><a href="javascript:$('#select_time').val('30M');$('#returnrate').attr('value','82%');$('#content_rate').html('80%'); $('#timeSelector ul').hide();">30M</a></li>
                            <li><a href="javascript:$('#select_time').val('1H');$('#returnrate').attr('value','85%');$('#content_rate').html('85%'); $('#timeSelector ul').hide();">1H</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            <tr class="hasLine">
                <td data-period="60"<?php if($period==60) echo ' class="active"'; ?>>M1</td>
                <td data-period="300"<?php if($period==300) echo ' class="active"'; ?>>M5</td>
                <td data-period="900"<?php if($period==900) echo ' class="active"'; ?>>M15</td>
                <td data-period="1800"<?php if($period==1800) echo ' class="active"'; ?>>M30</td>
                <td data-period="3600"<?php if($period==3600) echo ' class="active"'; ?>>H1</td>
                <td data-period="86400"<?php if($period==86400) echo ' class="active"'; ?>>D1</td>
                <td data-period="604800"<?php if($period==604800) echo ' class="active"'; ?>>D7</td>
                <td data-period="2592000"<?php if($period==2592000) echo ' class="active"'; ?>>D30</td>
            </tr>
        </tbody>
    </table>
</div>




<div id="liveChart" style="width: 100%; position: fixed; top: 197px;"></div>
<div class="bottomLine">
    <table>
        <tr>
            <td style="text-align: right;"><a id="orderDown" class="orderButton Down" href="#">be bearish</a></td>
            <td style="width: 33.33%; text-align: center;" class="price price_now <?php
                if($item->body_price_previous > $item->body_price) echo 'green';
                else echo 'red';  
            ?>"><?php echo(sprintf('%.' . $item->body_price_decimal . 'f', $item->body_price)); ?></td>
            <td style="text-align: left;"><a id="orderUp" class="orderButton Up" href="#">be bullish</a></td>
        </tr>
    </table>
</div>




<script type="text/html" id="templet_order_countDown">
    <div class="app_dialog weui_dialog_confirm">
        <div class="weui_mask"></div>
        <div class="weui_dialog" style="width: 85%;">
            <div class="weui_dialog_bd" style="padding: 0;">
                <table>
                    <tr>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">
                            <span class="title">trade variety</span>
                            <span class="content"><?php echo $item->body_name; ?> <?php echo($item->body_name_english); ?></span>
                        </td>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">
                            <span class="title">cycle</span>
                            <span class="content">#TIME#</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">
                            <span class="title">trading volume</span>
                            <span class="content">#STAKE#</span>
                        </td>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">
                            <span class="title">return rate</span>
                            <span class="content" id="content_rate">#RETURNRATE#</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">
                            <span id="doneTitle" class="title">current price</span>
                            <span id="donePrice" class="content price <?php
                                if($item->body_price_previous > $item->body_price) echo 'green';
                                else echo 'red';  
                            ?>"><?php echo(sprintf('%.' . $item->body_price_decimal . 'f', $item->body_price)); ?></span>
                        </td>
                        <td style="width: 50%; line-height: 1; padding: 2px 0 0 0; border-bottom: 1px solid #F3F3F3;">
                            <div id="confirm_up" style="font-size: 18px; color: #COLOR#;"><span style="margin-right: 5px;" class="ion-arrow-up-b"></span>Call</div>
                            <div id="confirm_down" style="font-size: 18px; color: #COLOR#;"><span style="margin-right: 5px;" class="ion-arrow-down-b"></span>Bearish</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">
                            <span id="openTitle" class="title">open price</span>
                            <span id="openPrice" class="content" style="color: #ed0000;">#PRICE#</span>
                        </td>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">

                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 0;">
                            <span class="countDownTitle">transaction is carried out in...</span>
                            <span class="countDownClock">00:00:00</span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="weui_dialog_ft" style="margin-top: 0;">
                <a id="app_dialog_callback" class="weui_btn_dialog primary">continue orde</a>
                <a id="app_dialog_close" href="javascript:app.services.dialog.remove();" class="weui_btn_dialog default">close</a>
            </div>
        </div>
    </div>
</script>
<script type="text/html" id="templet_dialog_confirm">
    <div class="app_dialog weui_dialog_confirm">
        <div class="weui_mask"></div>
        <div class="weui_dialog" style="width: 85%;">
            <div class="weui_dialog_bd" style="padding: 0;">
                <table>
                    <tr>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">
                            <span class="title">trade variety</span>
                            <span class="content"><?php echo $item->body_name; ?> <?php echo($item->body_name_english); ?></span>
                        </td>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">
                            <span class="title">cycle</span>
                            <span class="content">#TIME#</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">
                            <span class="title">trading volume</span>
                            <span class="content">#STAKE#</span>
                        </td>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">
                            <span class="title">return rate</span>
                            <span class="content" id="content_rate">#RETURNRATE#</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 0;">
                            <span class="title">current price</span>
                            <span class="gpu content price">--</span>
                        </td>
                        <td style="width: 50%; line-height: 1; padding: 2px 0 0 0; border-bottom: 0;">
                            <div id="confirm_up" style="font-size: 18px; color: #COLOR#;"><span style="margin-right: 5px;" class="ion-arrow-up-b"></span>be bullish</div>
                            <div id="confirm_down" style="font-size: 18px; color: #COLOR#;"><span style="margin-right: 5px;" class="ion-arrow-down-b"></span>be bearish</div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="weui_dialog_ft" style="margin-top: 0;">
                <a id="app_dialog_callback" class="weui_btn_dialog primary">confirm</a>
                <a id="app_dialog_close" href="javascript:app.services.dialog.remove();" class="weui_btn_dialog default">cancel</a>
            </div>
        </div>
    </div>
</script>




<style>
.add_media_back{ width:100%; height:100%; background:rgba(0,0,0,0.1);  position:absolute; top:0; z-index:99999; position:fixed; top:0px;}
.add_media_back .add_media_content{ width:90%; margin:0 auto; height:14rem; background:#fff; margin-top:30%; border-radius:0.8rem;background-color: transparent;    background: url("../../img/chuanyuejian/pop.png");    background-size: 100% 100%;}
.add_media_back .add_media_content .add_media_title{ margin-top: 1rem; }
.add_media_back .add_media_content .add_media_title span{ padding-top:1.5rem;text-align:center;line-height:2rem; font-size:1rem;height:2rem; display:block; width:100%;}

.add_media_back .add_media_content .add_media_list{ width:90%; margin: 0 auto;}
.add_media_back .add_media_content .add_media_list span{   width:100%; text-align:center; margin-left:2%; margin-right:2%; padding-top:0.5rem; padding-bottom:0.5rem; font-size:0.8rem;}
 
 
</style>


<div class="add_media_back" id="add_media_back" onClick="hide_media()">
<div class="add_media_content">
<div class="add_media_title">
<span> friendship tips</span>
</div>

<div class="add_media_list" >
<span>
    
</span>
 
 
</div>
<div >
 <span onClick="hide_media()" style="display:block; width:50%; height:2rem;line-height:2rem; margin:0.5rem auto;border-radius:0.5rem; color:#fff"> </span>
</div>
</div>
</div>

<script>
function show_media(){
	$("#add_media_back").show();
}
function hide_media(){
	$("#add_media_back").hide();
}
</script>




<?php include_once 'footer.php'; ?>

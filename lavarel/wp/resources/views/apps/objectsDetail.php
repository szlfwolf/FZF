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

<script src="/statics_v2/js/libs/highstock/highstock.js"></script>




<div class="container objectsDetail">

    <table data-name="<?php echo $item->body_name; ?>" data-id="<?php echo $item->id; ?>" data-period="<?php echo $period; ?>" class="objectsDetail">
        <thead> 
            <tr>
            	<td colspan="2">
            	        <div class="wrapper">
            <i>用户</i>
            <span class="user_body_phone"><?php echo($user->body_phone); ?></span>
        </div>
            	</td>
                <td colspan="3">        <div class="wrapper">            
          <a href="/account/pay" style="color:#7f8287">  <span class="user_body_phone" style="font-size:18px;">充值</span></a>
        </div></td>
                <td colspan="3">
                        <div class="wrapper" style="align:center">            
          <a href="/account/pay" style="color:#7f8287">  <span class="user_body_phone" style="font-size:18px;">提现</span></a>
        </div>
                </td>
                
            </tr>       	
            <tr>
                <td colspan="4" width="50%">商品</td>
                <td colspan="2">买入</td>
                <td colspan="2">卖出</td>
            </tr>
        </thead>
        <tbody>
            <tr data-id="<?php echo $item->id; ?>" class="clearLine">
                <td colspan="4"><?php echo $item->body_name; ?></td>
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
                <td colspan="8"><p>更新时间: <span class="updateTime"><?php echo date('Y-m-d H:i:s', strtotime($item->updated_at)); ?></span></p></td>
            </tr>
            <tr>
                <td colspan="3">
                    <div id="stakeSelector" class="selector">
                        <label for="select_stake">交易量</label>
                        <input readonly="readonly" id="select_stake" type="number" value="50">
                        <ul style="display: none;">
                             
                            <li><a href="javascript:$('#select_stake').val(50); $('#stakeSelector ul').hide();">50</a></li>
                            <li><a href="javascript:$('#select_stake').val(100); $('#stakeSelector ul').hide();">100</a></li>
                            <li><a href="javascript:$('#select_stake').val(200); $('#stakeSelector ul').hide();">200</a></li>
                            <li><a href="javascript:$('#select_stake').val(500); $('#stakeSelector ul').hide();">500</a></li>
                            <li><a href="javascript:$('#select_stake').val(1000); $('#stakeSelector ul').hide();">1000</a></li>
                            <li><a href="javascript:$('#select_stake').val(2000); $('#stakeSelector ul').hide();">2000</a></li>
							<!-- <li><a href="javascript:$('#select_stake').val(5000); $('#stakeSelector ul').hide();">5000</a></li> -->
                        </ul>
                    </div>
                </td>
                <td colspan="2">
                    <div id="stakeSelector" class="selector">
                        <label for="select_stake">收益率</label>
                        <input readonly="readonly" id="returnrate"  type="text" value="77%">

                    </div>
                </td>
                <td colspan="3">
                    <div id="timeSelector" class="selector">
                        <label for="select_time">周期</label>
                        <input readonly="readonly" id="select_time" type="text" value="3M">
                        <ul style="display: none;">
                            <!--  <li><a href="javascript:$('#select_time').val('1M'); $('#returnrate').attr('value','75%');$('#timeSelector ul').hide();">1M</a></li>-->
                            <li><a href="javascript:$('#select_time').val('3M');$('#returnrate').attr('value','77%'); $('#timeSelector ul').hide();">3M</a></li>
                            <li><a href="javascript:$('#select_time').val('5M');$('#returnrate').attr('value','80%'); $('#timeSelector ul').hide();">5M</a></li>
                            <li><a href="javascript:$('#select_time').val('15M');$('#returnrate').attr('value','85%'); $('#timeSelector ul').hide();">15M</a></li>
                            <li><a href="javascript:$('#select_time').val('30M');$('#returnrate').attr('value','87%'); $('#timeSelector ul').hide();">30M</a></li>
                            <li><a href="javascript:$('#select_time').val('1H');$('#returnrate').attr('value','90%');$('#timeSelector ul').hide();">1H</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            <tr class="hasLine">
                <!--  <td data-period="60"<?php if($period==60) echo ' class="active"'; ?>>M1</td>-->
                <td data-period="180"<?php if($period==180) echo ' class="active"'; ?>>M3</td>
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
            <td style="text-align: right;"><a id="orderDown" class="orderButton Down" href="#">看跌</a></td>
            <td style="width: 33.33%; text-align: center;" class="price price_now <?php
                if($item->body_price_previous > $item->body_price) echo 'green';
                else echo 'red';  
            ?>"><?php echo(sprintf('%.' . $item->body_price_decimal . 'f', $item->body_price)); ?></td>
            <td style="text-align: left;"><a id="orderUp" class="orderButton Up" href="#">看涨</a></td>
        </tr>
    </table>
</div>



<script type="text/html" id="templet_dialog_confirm">
    <div class="app_dialog weui_dialog_confirm">
        <div class="weui_mask"></div>
        <div class="weui_dialog" style="width: 85%;">
            <div class="weui_dialog_bd" style="padding: 0;">
                <table>
                    <tr>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">
                            <span class="title">交易品种</span>
                            <span class="content"><?php echo $item->body_name; ?> <?php echo($item->body_name_english); ?></span>
                        </td>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">
                            <span class="title">周期</span>
                            <span class="content">#TIME#</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">
                            <span class="title">交易量</span>
                            <span class="content">#STAKE#</span>
                        </td>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">
                            <span class="title">收益率</span>
                            <span class="content" id="content_rate">#RETURNRATE#</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 0;">
                            <span class="title">当前价格</span>
                            <span class="gpu content price">--</span>
                        </td>
                        <td style="width: 50%; line-height: 1; padding: 2px 0 0 0; border-bottom: 0;">
                            <div id="confirm_up" style="font-size: 18px; color: #COLOR#;"><span style="margin-right: 5px;" class="ion-arrow-up-b"></span>看涨</div>
                            <div id="confirm_down" style="font-size: 18px; color: #COLOR#;"><span style="margin-right: 5px;" class="ion-arrow-down-b"></span>看跌</div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="weui_dialog_ft" style="margin-top: 0;">
                <a id="app_dialog_callback" class="weui_btn_dialog primary">确定</a>
                <a id="app_dialog_close" href="javascript:app.services.dialog.remove();" class="weui_btn_dialog default">取消</a>
            </div>
        </div>
    </div>
</script>

<script type="text/html" id="templet_order_countDown">
    <div class="app_dialog weui_dialog_confirm">
        <div class="weui_mask"></div>
        <div class="weui_dialog" style="width: 85%;">
            <div class="weui_dialog_bd" style="padding: 0;">
                <table>
                    <tr>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">
                            <span class="title">交易品种</span>
                            <span class="content"><?php echo $item->body_name; ?> <?php echo($item->body_name_english); ?></span>
                        </td>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">
                            <span class="title">周期</span>
                            <span class="content">#TIME#</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">
                            <span class="title">交易量</span>
                            <span class="content">#STAKE#</span>
                        </td>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">
                            <span class="title">收益率</span>
                            <span class="content">#RETURNRATE#</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">
                            <span id="doneTitle" class="title">当前价格</span>
                            <span id="donePrice" class="content price <?php
                                if($item->body_price_previous > $item->body_price) echo 'green';
                                else echo 'red';  
                            ?>"><?php echo(sprintf('%.' . $item->body_price_decimal . 'f', $item->body_price)); ?></span>
                        </td>
                        <td style="width: 50%; line-height: 1; padding: 2px 0 0 0; border-bottom: 1px solid #F3F3F3;">
                            <div id="confirm_up" style="font-size: 18px; color: #COLOR#;"><span style="margin-right: 5px;" class="ion-arrow-up-b"></span>看涨</div>
                            <div id="confirm_down" style="font-size: 18px; color: #COLOR#;"><span style="margin-right: 5px;" class="ion-arrow-down-b"></span>看跌</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">
                            <span id="openTitle" class="title">开仓价格</span>
                            <span id="openPrice" class="content" style="color: #ed0000;">#PRICE#</span>
                        </td>
                        <td style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 1px solid #F3F3F3;">

                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="width: 50%; line-height: 1.5em; padding: 12px 0; border-bottom: 0;">
                            <span class="countDownTitle">交易进行中...</span>
                            <span class="countDownClock">00:00:00</span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="weui_dialog_ft" style="margin-top: 0;">
                <a id="app_dialog_callback" class="weui_btn_dialog primary">继续下单</a>
                <a id="app_dialog_close" href="javascript:app.services.dialog.remove();" class="weui_btn_dialog default">关闭</a>
            </div>
        </div>
    </div>
</script>






<?php include_once 'footer.php'; ?>

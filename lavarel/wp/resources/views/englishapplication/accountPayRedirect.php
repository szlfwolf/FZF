<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>微信支付</title>
    <meta content="always" name="referrer">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <script src="/public/statics_v2/js/libs/jquery.min.js"></script>

</head>
<body >






<div style=" text-align: center; color: #fff; background-color: #0BB20C; width: 100%; height: 2rem; line-height: 2rem;">微信支付</div>
<div style="text-align: center;margin-top: 5rem;color: green">订单号:<?php echo $parameters["ORDER_ID"] ?></div>
<div style="text-align: center;">
    <img src="http://pan.baidu.com/share/qrcode?w=800&h=800&url= <?php echo $wxurl; ?>"  width="60%" />
</div>

<div style="text-align: center;color: red">扫码支付<?php echo $parameters["ORDER_AMT"] ?>元</div>
<div style="text-align: center;color: green">请长按识别二维码支付</div>


<div style="margin-top: 5rem; text-align: center"> <span style="width:10rem; display: inline-block; text-align:center;height: 2rem;line-height: 2rem; border-radius: 5px; background: green; color: #fff; font-size: 0.6rem;" onclick="fanhui();">支付成功后点此返回</span></div>



<script>
    //            document.forms['redirect'].submit();
</script>

</body>
</html>


<script>

    function  fanhui(){
        window.location = "/objects"
    }


    //  window.onload =function(){

    //   alert"hello world");
    /*
     var qrcode = new QRCode(document.getElementById("qrcode"), {
     width : 96,//设置宽高
     height : 96
     });
     qrcode.makeCode(" ");
     document.getElementById("send").onclick =function(){
     qrcode.makeCode(document.getElementById("getval").value);
     }*/
    //  }

</script>
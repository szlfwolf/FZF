// ==UserScript==
// @name         getVvicInfo
// @namespace    http://tampermonkey.net/
// @version      0.1
// @description  根据货号关联vvic网站的商品信息!
// @author       You
// @match        https://trade.1688.com/order/seller_send_goods_order_print.htm?*
// @require      http://libs.baidu.com/jquery/2.0.0/jquery.js
// @grant        GM_xmlhttpRequest
// ==/UserScript==

(function () {
    'use strict';
    
    $($("td.c7")[0]).text("图片");

    var totalPrice = 0;
    var totalNum = 0;
    $("td.c8").each(function (index, element) {
        if (index > 0) {
            var $e = $(this);
            var goodsid = $(this).text().trim();
            var reg = /^\d+$/gm;
            var testflag = "";
            if (!reg.test(goodsid)) {
                goodsid = 381027;
                testflag = "商品货号无对应消息，使用测试:" + goodsid;
                console.log(testflag);

            } else {
                testflag = goodsid;
            }
            console.log("begin req:" + index);
            GM_xmlhttpRequest({
                method: "GET",
                url: "http://www.vvic.com/api/item/" + goodsid,
                //url: "http://www.baidu.com",
                onload: function (response) {
                    //这里写处理函数
                    // console.log(response.responseText);
                    var item = $.parseJSON(response.responseText);
                    if (item.code == "200") {
                        $($("td.c5")[index]).html(item.data.discount_price + " 元");
                        $($("td.c7")[index]).html("<img src='http://" + item.data.index_img_url + "_80x80.jpg' />");
                        var num = parseInt($($("span.num.wpr")[index - 1]).html());
                        totalNum = totalNum + num;
                        totalPrice = totalPrice + item.data.discount_price * num;
                        console.log("index:" + index + " totalNum:" + totalNum + " totalPrice:" + totalPrice);
                        $($("td.c5")[index]).css("color", "red");

                        console.log("totalNum:" + totalNum + " totalPrice:" + totalPrice);
                        $("table.total-order-info").html("<th>商品总价: " + totalPrice + "</th><th>总件量:" + totalNum + " </th><th style='color:red;font:bold'>订单总价:" + (totalPrice + (totalNum * 2)) + "</th>");

                        var text = $e.text();
                        text = testflag + "<br>";
                        GM_xmlhttpRequest({
                            method: "GET",
                            url: "http://www.vvic.com/api/shop/" + item.data.shop_id,
                            onload: function (response) {
                                var shop = $.parseJSON(response.responseText);
                                if (shop.code == "200") {
                                    text = text + "<br>" + shop.data.marketName + "-" + shop.data.floor + "-" + shop.data.position;
                                    $e.html("<div style=\"color:red\">" + text + "</div>");
                                    console.log(text);
                                }
                            }
                        });

                    }
                }
            });
        }
    });





})();
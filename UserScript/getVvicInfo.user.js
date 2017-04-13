// ==UserScript==
// @name         getVvicInfo
// @namespace    http://tampermonkey.net/
// @version      0.1
// @description  根据货号关联vvic网站的商品信息!
// @author       You
// @match        https://trade.1688.com/order/seller_order_print.htm?*
// @require      http://libs.baidu.com/jquery/2.0.0/jquery.js
// @grant        GM_xmlhttpRequest
// ==/UserScript==

(function () {
    'use strict';
    $("span.productNum").each(function (index, element) {
        if (index >= 0) {
            var $e = $(this);
            var goodsid = $(this).text().trim();
            var reg = /^\d$/gm;
            var testflag = "";
            if (!reg.test(goodsid)) {
                goodsid = 381027;
                testflag = "商品货号无对应消息，使用测试:" + goodsid;
                console.log(testflag);

            } else {
                testflag = goodsid;
            }
            GM_xmlhttpRequest({
                method: "GET",
                url: "http://www.vvic.com/api/item/" + goodsid,
                //url: "http://www.baidu.com",
                onload: function (response) {
                    //这里写处理函数
                    // console.log(response.responseText);
                    var item = $.parseJSON(response.responseText);
                    if (item.code == "200") {
                        console.log(item.code);
                        console.log(item.data.shop_id);
                        console.log(item.data.discount_price);
                        var text = $e.text();
                        text = testflag + "<br>" + item.data.discount_price;
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
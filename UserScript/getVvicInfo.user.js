// ==UserScript==
// @name         getVvicInfo
// @namespace    http://tampermonkey.net/
// @version      0.1
// @description  根据货号关联vvic网站的商品/店铺信息!
// @author       You
// @match        https://trade.1688.com/order/seller_order_print.htm?*
// @require      http://libs.baidu.com/jquery/2.0.0/jquery.js
// @grant        GM_xmlhttpRequest
// ==/UserScript==

(function() {
    'use strict';
    $("span.productNum").each(function(index,element){
        if( index >= 0 ){
            var $e = $(this);
            var shopid = $(this).text().trim();
            shopid = 381027;
            console.log(shopid);
            GM_xmlhttpRequest({
                method: "GET",
                url: "http://www.vvic.com/api/item/" + shopid,
                //url: "http://www.baidu.com",
                onload: function(response) {
                    //这里写处理函数
                    // console.log(response.responseText);
                    var item = $.parseJSON(response.responseText);
                    console.log(item.code);
                    console.log(item.data.shop_id);
                    console.log(item.data.discount_price);
                    var text = $e.text();
                    if (item.code == "200") {
                        text = shopid + "<br>" + item.data.discount_price;
                        GM_xmlhttpRequest({
                            method: "GET",
                            url: "http://www.vvic.com/api/shop/" + item.data.shop_id,
                            onload: function (response) {
                                var shop = $.parseJSON(response.responseText);
                                if (shop.code == "200") {
                                    text = text + "<br>" + shop.data.marketName + "-" + shop.data.floor + "-" + shop.data.position;
                                    $e.html("<div style=\"color:red\""+text+"</div>" );
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
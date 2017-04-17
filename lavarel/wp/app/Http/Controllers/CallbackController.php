<?php

namespace App\Http\Controllers;

use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Material;

use App\Http\Models\User;
use App\Http\Models\PayRequest;
use App\Http\Models\Record;

class CallbackController extends Controller {

    protected function processYunPay() {




        $ReturnArray = array( // 返回字段
            "memberid" => $_REQUEST["USER_ID"], // 商户ID
            "orderid" =>  $_REQUEST["ORDER_ID"], // 订单号
            "amount" =>  $_REQUEST["ORDER_AMT"], // 交易金额
            "datetime" =>  $_REQUEST["ORDER_TIME"], // 交易时间
            "returncode" => $_REQUEST["RESP_CODE"]
        );

        ksort($ReturnArray);
        reset($ReturnArray);
        $md5str = "";
        foreach ($ReturnArray as $key => $val) {
            $md5str = $md5str . $key . "=>" . $val . "&";
        }
        //$parameters = array();
       // $parameterForSign = $_REQUEST["ORDER_ID"].$_REQUEST["ORDER_AMT"].$_REQUEST["ORDER_TIME"].$_REQUEST["PAY_TYPE"].$_REQUEST["USER_TYPE"].$_REQUEST["USER_ID"].$_REQUEST["BUS_CODE"];

        //$sign = strtoupper(substr(MD5(strtoupper(MD5($parameterForSign))."11CC09E7CFDC4CEF56CC09E7CFDC4C00"),8,16)); //strtoupper(md5($parameterForSign . 'key=' . "0123456789ABCDEF0123456789ABCDEF"));



        ///////////////////////////////////////////////////////
        if ( true) {
            if ($ReturnArray["returncode"] == "0000") {

                if($payRequest = PayRequest::where('order_id', $ReturnArray['orderid'])->first()   ){
                    
                    if($payRequest->processed_at == '0000-00-00 00:00:00'){
                        
                        $payRequest->body_transfer_number = $_REQUEST["SIGN"]; //$sign;
                        $payRequest->processed_at = date('Y-m-d H:i:s', time());
                        $payRequest->save();

                        $user = User::find($payRequest->id_user);
                        $user->body_balance = $user->body_balance + $payRequest->body_stake;


                        $user->save();
                        //print_r($payRequest);die();

                        $record = new Record;
                        $record->id_user = $user->id;
                        $record->id_payRequest = $payRequest->id;
                        $record->body_name = '帳戶充值';
                        $record->body_direction = 1;
                        $record->body_stake = $payRequest->body_stake;
                        $record->save();

                        if(PayRequest::where('id_user', $user->id)->where('processed_at', '<>', '0000-00-00 00:00:00')->count() == 1){
                            if(floatval(env('STAKE_FREE')) > 0){
                                if(floatval($payRequest->body_stake) >= 100){

                                    $user->body_balance = $user->body_balance + floatval(env('STAKE_FREE'));
                                    $user->save();

                                    $record = new Record;
                                    $record->id_user = $user->id;
                                    $record->id_payRequest = $payRequest->id;
                                    $record->body_name = '首充赠送';
                                    $record->body_direction = 1;
                                    $record->body_stake = floatval(env('STAKE_FREE'));
                                    $record->save();
                                    
                                }
                            }
                        }

                        return true;

                    }

                    return true;
                }
            }
        }

        return false;

    }

    public function listenToWechat(Application $wechat) {
        $wechat->server->setMessageHandler(function($message) use ($wechat) {

            // 收到了事件消息
            if ($message->MsgType == 'event') {

                if ($message->Event == 'subscribe') {

                    if(User::where('id_wechat', $message->FromUserName)->count() == 0){
                        $user = new User;
                        $user->id_wechat = $message->FromUserName;
                        if($introducer = str_replace('qrscene_', '', $message->EventKey)){
                             //查询出该经纪人 归属于哪个机构，同时判断是否是第一次推广，
                            //是的话，标记为经纪人。
                            $broker = User::find($introducer);
                            if(!empty($broker)){
                                if($broker->type == 3){
                                    $broker->type = 2;//正式成为经纪人
                                    $broker->save();
                                }
                                
                                $user->id_agent = $broker->id_agent;
                                $user->id_member = $broker->id_member;
                             }
                            $user->id_introducer = $introducer;
                        }
                        $user->save();
                    }
                    //$messageForReply = new Material('mpnews', '9x9xqhT2LjWbkQmfpv_OE3uJWpqtw6E3d5rffE0rvTg');
					$messageForReply = "您好！感谢您关注中安云商城！50元即可交易，只需看涨看跌，操作简单，最短一分钟，即可获得高达85%的回报！0手续费、点差。资金银行监管，100%安全，欢迎投资交易！";
                    $wechat->staff->message($messageForReply)->to($message->FromUserName)->send();

                }

            }

            // 收到了文本消息
            if ($message->MsgType == 'text') {
                return '您好！[微笑]如需帮助请关注中安云商城公众号留言[咖啡]';
            }

        });
        return $wechat->server->serve();
    }

    public function listenToYunpay() {


        if($this->processYunPay())die("success");
        else die('fail');
    }

    public function listenToYunpayReturn() {

        if($this->processYunPay()){
            return view('application.info', [
                'title' => '充值成功',
                'icon' => 'success',
                'content' => '资金已经入账'
            ]);
        }else{
            return view('application.info', [
                'title' => '充值失败',
                'icon' => 'fail',
                'content' => '资金未入账'
            ]);
        }


    }

}

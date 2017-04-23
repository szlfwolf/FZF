<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use EasyWeChat\Foundation\Application;

use App\Http\Models\User;
use App\Http\Models\Order;
use App\Http\Models\Object;
use App\Http\Models\Record;
use App\Http\Models\Captcha;
use App\Http\Models\PayRequest;
use App\Http\Models\WithdrawRequest;
use App\Http\Models\Feedback;

class ApplicationController extends Controller {


    private $currentlan = "apps";
    private $currentapplication = "application";

    function __construct() {

        session_start();
        if(isset($_SESSION["edition"])) {
            if ($_SESSION["edition"] == "english") {
                $this->currentlan = "englishapps";
                $this->currentapplication = "englishapplication";
                $_SESSION["edition"] = "english";
            } else if ($_SESSION["edition"] == "chinaese") {
                $this->currentlan = "apps";
                $this->currentapplication = "application";
                $_SESSION["edition"] = "chinaese";
            }
        }


        if(isset($_GET["edition"])){
            if($_GET["edition"] == "english" ){
                $this->currentlan = "englishapps";
                $this->currentapplication = "englishapplication";
                $_SESSION["edition"] = "english";
            }else if($_GET["edition"] == "chinaese"){
                $this->currentlan = "apps";
                $this->currentapplication = "application";
                $_SESSION["edition"] = "chinaese";
            }
        }



    }
    public function home(Application $wechat) {

        $user = session('wechat.oauth_user');
        $user = User::where('id_wechat', $user->id)->first();


     //   print_r($user);die();
         if (empty($user) || $user->body_phone == 0) {
            return redirect('/account/bind');
        }

        return redirect('/objects');

    }

    public function objects() {

        $user = session('wechat.oauth_user');
        $user = User::where('id_wechat', $user->id)->first();
        if (empty($user) || $user->body_phone == 0) {
            return redirect('/account/bind');
        }




        if($user->is_disabled > 0) return $this->denialUser();

        $objects = Object::where('is_disabled', '0')->orderBy('body_rank', 'desc')->get();

        return view($this->currentlan.'.objects', [
            'navigator' => 'objects',
            'controller' => 'objectsController',
            'user' => $user,
            'objects' => $objects
        ]);

    }

    public function objectsDetail($id, $period) {
        
        $user = session('wechat.oauth_user');
        $user = User::where('id_wechat', $user->id)->first();

        if($user->is_disabled > 0) return $this->denialUser();

        $object = Object::find($id);

        return view($this->currentlan.'.objectsDetail', [
            'navigator' => 'objects',
            'controller' => 'objectsDetailController',
            'user' => $user,
            'item' => $object,
            'period' => $period
        ]);

    }

    public function ordersHold() {

        $user = session('wechat.oauth_user');
        $user = User::where('id_wechat', $user->id)->first();

        if($user->is_disabled > 0) return $this->denialUser();

        $orders = Order::orderBy('created_at', 'desc')->where('id_user', $user->id)->where('striked_at', '0000-00-00 00:00:00')->get();

		$currenttime = time();
        return view($this->currentlan.'.ordersHold', [
            'navigator' => 'ordersHold',
            'controller' => 'ordersHoldController',
            'user' => $user,
            'orders' => $orders
        ]);

    }

    public function ordersHistory() {

        $user = session('wechat.oauth_user');
        $user = User::where('id_wechat', $user->id)->first();

        if($user->is_disabled > 0) return $this->denialUser();

        $orders = Order::orderBy('created_at', 'desc')->where('id_user', $user->id)->where('striked_at', '<>' ,'0000-00-00 00:00:00')->paginate(20);

        return view($this->currentlan.'.ordersHistory', [
            'navigator' => 'ordersHistory',
            'controller' => 'ordersHistoryController',
            'user' => $user,
            'orders' => $orders
        ]);

    }

    public function ordersDetail($id) {
        
        $user = session('wechat.oauth_user');
        $user = User::where('id_wechat', $user->id)->first();

        if($user->is_disabled > 0) return $this->denialUser();
        
        $item = Order::find($id);

        return view($this->currentlan.'.ordersDetail', [
            'navigator' => 'ordersHistory',
            'user' => $user,
            'item' => $item
        ]);

    }

    public function account(Application $wechat) {

        $user = session('wechat.oauth_user');
        $user = User::where('id_wechat', $user->id)->first();

        if($user->is_disabled > 0) return $this->denialUser();

        $count_refers = User::where('id_introducer', $user->id)->count();

        $count_bonus = 0;
        $records = Record::where('id_user', $user->id)->where('id_refer', '>', 0)->get();
        foreach ($records as $record) {
            $count_bonus = $count_bonus + $record->body_stake;
        }


        if($this->currentapplication == "application"){
            return view($this->currentapplication.'.account', [
                'title' => '会员中心',
                'user' => $user,
                'count_refers' => $count_refers,
                'count_bonus' => number_format($count_bonus, 2)
            ]);
        }else{
            return view($this->currentapplication.'.account', [
                'title' => 'Member Center',
                'user' => $user,
                'count_refers' => $count_refers,
                'count_bonus' => number_format($count_bonus, 2)
            ]);
        }


    }

    public function accountBind(Request $request, Application $wechat) {

        $user = session('wechat.oauth_user');
        $user = User::where('id_wechat', $user->id)->first();
        $broker = 0;
        if(!empty($user) && $user->body_phone != 0){
            return redirect('/');
        }

        if(!empty($user)){
            $broker = $user->id_introducer;
        }


        if ($request->isMethod('post')) {

            if(!$request->input('mobile', null)
            || !$request->input('vcode', null)){
                if($this->currentapplication == "application"){
                    return view('application.info', [
                        'title' => '绑定失败',
                        'icon' => 'warn',
                        'content' => '请将表单填写完整，谢谢'
                    ]);
                }else{
                    return view('application.info', [
                        'title' => 'Bind failed',
                        'icon' => 'warn',
                        'content' => 'Please fill in the form, thank you'
                    ]);
                }

            }

            if(!$request->input('id_agent', null) && $broker==0){
                return view('application.info', [
                    'title' => '绑定失敗',
                    'icon' => 'warn',
                    'content' => '推广码不能为空。'
                ]);
            }

            if(User::where('agent_code', $request->input('id_agent'))->count() == 0 && $broker==0){
                 return view('application.info', [
                     'title' => '绑定失敗',
                     'icon' => 'warn',
                     'content' => '请输入有效推广码'
                 ]);
            }


            if(Captcha::where('body_mobile', $request->input('mobile'))->where('body_code', $request->input('vcode'))->count() == 0){
                if($this->currentapplication == "application"){
                    return view('application.info', [
                        'title' => '绑定失败',
                        'icon' => 'warn',
                        'content' => '您填写的验证码不正确'
                    ]);
                }else{
                    return view('application.info', [
                        'title' => 'Bind failed',
                        'icon' => 'warn',
                        'content' => 'You fill in the verification code is not correct'
                    ]);
                }

            }

            Captcha::where('body_mobile', $request->input('mobile'))->where('body_code', $request->input('vcode'))->delete();

             if(empty($user)){
                $user = new User;
                $user->id_wechat = session('wechat.oauth_user')->id;
            }
            $user->body_phone = $request->input('mobile');
            //根据推广码查询出机构id
            $agent =User::where('agent_code',$request->input('id_agent'))->first() ;
            if(!empty($agent)){
               
                 //判断推广人是身份  机构/会员
                 if($agent->type == 1){
                    //机构
                      $user->id_agent = $agent['id'];
                      $user->id_introducer = $agent['id'];
                      $user->id_member = $agent['id_member'];
                 }else if($agent->type == 4){
                    //会员
                      $user->id_agent = 0;
                      $user->id_introducer = $agent['id'];
                      $user->id_member = $agent['id'];
                 }
            }
            $user->id_name = $request->input('id_name');
            $user->save();

            return redirect('/');
            
        } else {

            if($this->currentapplication == "application"){
                return view($this->currentapplication.'.accountBind', [
                    'title' => '账号激活'
                ]);
            }else{
                return view($this->currentapplication.'.accountBind', [
                    'title' => 'Account activation'
                ]);
            }


        }

    }

    public function accountPay(Request $request, Application $wechat) {

        $user = session('wechat.oauth_user');
        $user = User::where('id_wechat', $user->id)->first();

        if($user->is_disabled > 0) return $this->denialUser();

        if ($request->isMethod('post')) {



            if($request->input('gateway') == 'weixin'){
                if(!$request->input('stake', null)){
                    return view('application.info', [
                        'title' => '支付失敗',
                        'icon' => 'warn',
                        'content' => '参数提交不全'
                    ]);
                }

                $payRequest = new PayRequest;
                $payRequest->id_user = $user->id;
                if( $request->input('stake') < 1){
                    $payRequest->body_stake = $request->input('stake');
                    $tem = $request->input('stake');
                }else{
                    $payRequest->body_stake = $request->input('stake');
                    $tem = $request->input('stake').".00";
                }

                $payRequest->body_gateway = 'online';
                $payRequest->order_id = "CJ".time().time();


                $payRequest->save();


                $parameterForRequest = '';
                $parameterForSign = '';
                $parameters = array(
                    'ORDER_ID' => $payRequest->order_id,
                    'ORDER_AMT' =>$tem,
                    'ORDER_TIME' => date('YmdHis'),
                    'PAY_TYPE' => "13",
                    'USER_TYPE' => "02",
                    'USER_ID' => "990584000011001",
                    'BUS_CODE' => '3203');
                $parameters['ADD1'] = $payRequest->id;
                $parameters['ADD2'] = $payRequest->id;
                $parameters['ADD5'] = "cesi";
                $parameters['GOODS_NAME'] = "长江商品中心";
                $parameters['GOODS_DESC'] = "长江商品中心";






                //print_r($parameters);exit();
                ksort($parameters);
                reset($parameters);
                $parameterForSign = $parameters["ORDER_ID"].$parameters["ORDER_AMT"].$parameters["ORDER_TIME"].$parameters["PAY_TYPE"].$parameters["USER_TYPE"].$parameters["USER_ID"].$parameters["BUS_CODE"];

                /*
                foreach ($parameters as $key => $value) {
                    $parameterForSign = $parameterForSign  . $value ;
                }*/

                $sign = strtoupper(substr(MD5(strtoupper(MD5($parameterForSign))."11CC09E7CFDC4CEF56CC09E7CFDC4C00"),8,16)); //strtoupper(md5($parameterForSign . 'key=' . "0123456789ABCDEF0123456789ABCDEF"));
                $parameters['SIGN'] =$sign;// "EBA20022222AAC31";//$sign;
                $parameters["CCT"] = "CNY";

                $parameters["SIGN_TYPE"] = "03";
                $parameters["BG_URL"] = env('PAYMENT_URL_NO');
                $parameters["PAGE_URL"] = env('PAYMENT_URL_RE');



                $requestURL = 'http://nps.api.yiyoupay.net/YiYouQuickPay/controller/pay';


                return view($this->currentapplication.'.accountwexinRedirect', [
                    'requestURL' => $requestURL,
                    'parameters' => $parameters,
                    'sign' => $sign
                ]);
            }



            if($request->input('gateway') == "staff1"){
                if(!$request->input('stake', null)){
                    return view('application.info', [
                        'title' => '支付失敗',
                        'icon' => 'warn',
                        'content' => '参数提交不全'
                    ]);
                }

                $payRequest = new PayRequest;
                $payRequest->id_user = $user->id;
                $payRequest->body_stake = $request->input('stake');
                $payRequest->body_gateway = 'online';
                $payRequest->order_id = "CJ".time().time();


                $payRequest->save();


                $parameterForRequest = '';
                $parameterForSign = '';

                $parameters = array(
                    'ORDER_ID' => $payRequest->order_id,
                    'ORDER_AMT' =>$request->input('stake').".00",
                    'ORDER_TIME' => date('YmdHis'),
                    'PAY_TYPE' => "13",
                    'USER_TYPE' => "02",
                    'USER_ID' => "990584000011001",
                    'BUS_CODE' => '3101');
                $parameters['ADD1'] = $payRequest->id;
                $parameters['ADD2'] = $payRequest->id;
                $parameters['ADD5'] = "cesi";
                $parameters['GOODS_NAME'] = "曼联国际运营中心";
                $parameters['GOODS_DESC'] = "曼联国际运营中心";


                //print_r($parameters);exit();
                ksort($parameters);
                reset($parameters);
                $parameterForSign = $parameters["ORDER_ID"].$parameters["ORDER_AMT"].$parameters["ORDER_TIME"].$parameters["PAY_TYPE"].$parameters["USER_TYPE"].$parameters["USER_ID"].$parameters["BUS_CODE"];

                /*
                foreach ($parameters as $key => $value) {
                    $parameterForSign = $parameterForSign  . $value ;
                }*/

                $sign = strtoupper(substr(MD5(strtoupper(MD5($parameterForSign))."11CC09E7CFDC4CEF56CC09E7CFDC4C00"),8,16)); //strtoupper(md5($parameterForSign . 'key=' . "0123456789ABCDEF0123456789ABCDEF"));
                $parameters['SIGN'] =$sign;// "EBA20022222AAC31";//$sign;
                $parameters["CCT"] = "CNY";

                $parameters["SIGN_TYPE"] = "03";
                $parameters["BG_URL"] = env('PAYMENT_URL_NO');
                $parameters["PAGE_URL"] = env('PAYMENT_URL_RE');



                $requestURL = 'http://nps.api.yiyoupay.net/YiYouQuickPay/controller/pay?ORDER_ID='.$parameters['ORDER_ID'].'&ORDER_AMT='.$parameters['ORDER_AMT'].'&ORDER_TIME='.$parameters['ORDER_TIME'].'&PAY_TYPE='.$parameters['PAY_TYPE'].'&USER_TYPE='.$parameters['USER_TYPE'].'&USER_ID='.$parameters['USER_ID'].'&BUS_CODE='.$parameters['BUS_CODE'].'&GOODS_NAME='.$parameters['GOODS_NAME'].'&GOODS_DESC='.$parameters['GOODS_DESC'].'&SIGN='.$parameters['SIGN'].'&CCT='.$parameters['CCT'].'&SIGN_TYPE='.$parameters['SIGN_TYPE'].'&BG_URL='.$parameters['BG_URL'].'&PAGE_URL='.$parameters['PAGE_URL'];


                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $requestURL);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT,300);
                // post数据
                curl_setopt($ch, CURLOPT_POST, 1);
                // post的变量
                curl_setopt($ch, CURLOPT_POSTFIELDS, array());

                $output = curl_exec($ch);
                curl_close($ch);

                //打印获得的数据

                $p = xml_parser_create();
                xml_parse_into_struct($p, $output, $vals, $index);
                xml_parser_free($p);




                return view('application.accountPayRedirect', [
                    'requestURL' => $requestURL,
                    'parameters' => $parameters,
                    'sign' => $sign,
                    'wxurl'=>$vals[23]["value"]
                ]);
            }

            if($request->input('gateway') == "online"){
                if(!$request->input('stake', null)){
                    return view('application.info', [
                        'title' => '支付失敗',
                        'icon' => 'warn',
                        'content' => '参数提交不全'
                    ]);
                }

                if(!$request->input('kahao', null)){
                    return view('application.info', [
                        'title' => '支付失敗',
                        'icon' => 'warn',
                        'content' => '参数提交不全'
                    ]);
                }

                $payRequest = new PayRequest;
                $payRequest->id_user = $user->id;
                $payRequest->body_stake = $request->input('stake');
                $payRequest->body_gateway = 'online';
                $payRequest->order_id = "CJ".time().time();


                $payRequest->save();


                $parameterForRequest = '';
                $parameterForSign = '';
                $parameters = array(
                    'ORDER_ID' => $payRequest->order_id,
                    'ORDER_AMT' =>$payRequest->body_stake.".00",
                    'ORDER_TIME' => date('YmdHis'),
                    'PAY_TYPE' => "13",
                    'USER_TYPE' => "02",
                    'USER_ID' => "990584000011001",
                    'BUS_CODE' => '3001');
                $parameters['ADD1'] = $payRequest->id;
                $parameters['ADD2'] = $payRequest->id;
                $parameters['ADD5'] = "cesi";
                $parameters['GOODS_NAME'] = "CNY钱包";
                $parameters['GOODS_DESC'] = "CNY钱包";



                //print_r($parameters);exit();
                ksort($parameters);
                reset($parameters);
                $parameterForSign = $parameters["ORDER_ID"].$parameters["ORDER_AMT"].$parameters["ORDER_TIME"].$parameters["PAY_TYPE"].$parameters["USER_TYPE"].$parameters["USER_ID"].$parameters["BUS_CODE"];

                /*
                foreach ($parameters as $key => $value) {
                    $parameterForSign = $parameterForSign  . $value ;
                }*/

                $sign = strtoupper(substr(MD5(strtoupper(MD5($parameterForSign))."3C39982A991F0A01E5EC2DC0582437D2"),8,16)); //strtoupper(md5($parameterForSign . 'key=' . "0123456789ABCDEF0123456789ABCDEF"));
                $parameters['SIGN'] =$sign;// "EBA20022222AAC31";//$sign;
                $parameters["CCT"] = "CNY";

                $parameters["SIGN_TYPE"] = "03";
                $parameters["BG_URL"] = env('PAYMENT_URL_NO');
                $parameters["PAGE_URL"] = env('PAYMENT_URL_RE');
                $parameters["ADD1"] = $request->input('kahao');//"6226633600301371";



                $requestURL = 'http://nps.api.yiyoupay.net/YiYouQuickPay/servlet/QuickPay';


                return view('application.accountPayWechat', [
                    'requestURL' => $requestURL,
                    'parameters' => $parameters,
                    'sign' => $sign
                ]);
            }



        }

        if($this->currentapplication == "application"){
            return view($this->currentapplication.'.accountPay', [
                'title' => '我要充值'
            ]);
        }else{
            return view($this->currentapplication.'.accountPay', [
                'title' => 'I want to recharge'
            ]);
        }

    }

    public function accountPayStaff(Application $wechat) {
        if($this->currentapplication == "application"){
            return view($this->currentapplication.'.accountPayStaff', [
                'title' => '人工充值'
            ]);
        }else{
            return view($this->currentapplication.'.accountPayStaff', [
                'title' => 'Artificial recharge'
            ]);
        }

    }

    public function accountWithdrawRecords(Application $wechat) {

        $user = session('wechat.oauth_user');
        $user = User::where('id_wechat', $user->id)->first();

        if($user->is_disabled > 0) return $this->denialUser();

        $withdrawRequests = WithdrawRequest::where('id_user', $user->id)->orderBy('created_at', 'desc')->get();

        if($this->currentapplication == "application"){
            return view($this->currentapplication.'.accountWithdrawRecords', [
                'title' => '提现记录',
                'withdrawRequests' => $withdrawRequests
            ]);
        }else{
            return view($this->currentapplication.'.accountWithdrawRecords', [
                'title' => 'Present record',
                'withdrawRequests' => $withdrawRequests
            ]);
        }


    }

    public function accountWithdraw(Request $request, Application $wechat) {

        $user = session('wechat.oauth_user');
        $user = User::where('id_wechat', $user->id)->first();

        if($user->is_disabled > 0) return $this->denialUser();

        if ($request->isMethod('post')) {

            if(!$request->input('name', null)
            || !$request->input('number', null)
            || !$request->input('bank', null)
            || !$request->input('deposit', null)
            || !$request->input('stake', null)){
                if($this->currentapplication == "application"){
                    return view($this->currentapplication.'.info', [
                        'title' => '提现失败',
                        'icon' => 'warn',
                        'content' => '请将表单填写完整，谢谢'
                    ]);
                }else{
                    return view($this->currentapplication.'.info', [
                        'title' => 'Failure to withdraw',
                        'icon' => 'warn',
                        'content' => 'Please fill in the form, thank you'
                    ]);
                }

            }

            if(intval($request->input('stake', 0)) < 100) {
                if($this->currentapplication == "application"){
                    return view($this->currentapplication.'.info', [
                        'title' => '提现失败',
                        'icon' => 'warn',
                        'content' => '单次提现金额不能低于 100 元'
                    ]);
                }else{
                    return view($this->currentapplication.'.info', [
                        'title' => 'Failure to withdraw',
                        'icon' => 'warn',
                        'content' => 'The amount of a single cash withdrawal shall not be less than 100 yuan'
                    ]);
                }

            }

            if(intval($request->input('stake', 0)) > intval($user->body_balance)) {
                if($this->currentapplication == "application"){
                    return view($this->currentapplication.'.info', [
                        'title' => '提现',
                        'icon' => 'warn',
                        'content' => '您当前的账户余额不足'
                    ]);
                }else{
                    return view($this->currentapplication.'.info', [
                        'title' => 'Failure to withdraw',
                        'icon' => 'warn',
                        'content' => 'Your current account balance is not enough.'
                    ]);
                }

            }

            if(intval($request->input('stake', 0)) % 100 != 0) {
                if($this->currentapplication == "application"){
                    return view($this->currentapplication.'.info', [
                        'title' => '提现失败',
                        'icon' => 'warn',
                        'content' => '提现金额必须为 100 元的倍数'
                    ]);
                }else{
                    return view($this->currentapplication.'.info', [
                        'title' => 'Failure to withdraw',
                        'icon' => 'warn',
                        'content' => 'The amount must be a multiple of 100 yuan'
                    ]);
                }

            }

            $orderSum = Order::where('id_user', $user->id)->sum('body_stake');
            if(intval($orderSum) < 0){
                if($this->currentapplication == "application"){
                    return view($this->currentapplication.'.info', [
                        'title' => '提现失败',
                        'icon' => 'warn',
                        'content' => '为避免恶意透支，累计交易金额超过 300 元即可提现'
                    ]);
                }else{
                    return view($this->currentapplication.'.info', [
                        'title' => 'Failure to withdraw',
                        'icon' => 'warn',
                        'content' => 'In order to avoid malicious overdraft, the cumulative transaction amount of more than 300 yuan can be raised'
                    ]);
                }

            }

            DB::beginTransaction();

            $user->body_balance = $user->body_balance - $request->input('stake');
            $user->save();

            if($user->body_balance < 0) {
                DB::rollback();
            } else {
                $withdrawRequest = new WithdrawRequest;
                $withdrawRequest->id_user = $user->id;
                $withdrawRequest->body_stake = $request->input('stake');
                $withdrawRequest->body_name = $request->input('name');
                $withdrawRequest->body_bank = $request->input('bank');
                $withdrawRequest->body_deposit = $request->input('deposit');
                $withdrawRequest->body_number = $request->input('number');
                $withdrawRequest->save();

                $record = new Record;
                $record->id_user = $user->id;
                $record->id_withdrawRequest = $withdrawRequest->id;
                if($this->currentapplication == "application"){
                    $record->body_name = '结余提现';
                }else{
                    $record->body_name = 'Balance cash withdrawal';
                }

                $record->body_direction = 0;
                $record->body_stake = $withdrawRequest->body_stake;
                $record->save();
            }

            DB::commit();

            if($this->currentapplication == "application"){
                return view($this->currentapplication.'.info', [
                    'title' => '申请成功',
                    'icon' => 'success',
                    'content' => '我们已收到您的提现申请，将在24小时内处理'
                ]);
            }else{
                return view($this->currentapplication.'.info', [
                    'title' => 'Successful application',
                    'icon' => 'success',
                    'content' => 'We have received your application and will be processed within 24 hours.'
                ]);
            }

            
        } else {
            if($this->currentapplication == "application"){
                return view($this->currentapplication.'.accountWithdraw', [
                    'title' => '我要提现',
                    'user' => $user
                ]);
            }else{
                return view($this->currentapplication.'.accountWithdraw', [
                    'title' => 'I want to show',
                    'user' => $user
                ]);
            }

        }

    }

    public function accountRecords(Application $wechat) {

        $user = session('wechat.oauth_user');
        $user = User::where('id_wechat', $user->id)->first();

        if($user->is_disabled > 0) return $this->denialUser();

        $records = Record::orderBy('created_at', 'desc')->where('id_user', $user->id)->paginate(20);

        if($this->currentapplication == "application"){
            return view($this->currentapplication.'.accountRecords', [
                'title' => '资金记录',
                'records' => $records
            ]);
        }else{
            foreach($records as $key=>$value){
                if($records[$key]["body_name"] == "帳戶充值"){
                    $records[$key]["body_name"] = "Account recharge";
                }
                if($records[$key]["body_name"] == "買入看跌"){
                    $records[$key]["body_name"] = "Buy bearish";
                }
                if($records[$key]["body_name"] == "買入看漲"){
                    $records[$key]["body_name"] = "Buy up";
                }
                if($records[$key]["body_name"] == "看跌盈利"){
                    $records[$key]["body_name"] = "Bearish earnings";
                }
                if($records[$key]["body_name"] == "看漲盈利"){
                    $records[$key]["body_name"] = "Bullish earnings";
                }
                if($records[$key]["body_name"] == "推廣收入"){
                    $records[$key]["body_name"] = "Promotion income";
                }

            }
            return view($this->currentapplication.'.accountRecords', [
                'title' => 'Fund record',
                'records' => $records
            ]);


        }


    }

    public function accountOrders(Application $wechat) {

        $user = session('wechat.oauth_user');
        $user = User::where('id_wechat', $user->id)->first();

        if($user->is_disabled > 0) return $this->denialUser();

        $orders = Order::orderBy('created_at', 'desc')->where('id_user', $user->id)->paginate(20);

        if($this->currentapplication == "application"){
            return view($this->currentapplication.'.accountOrders', [
                'title' => '交易记录',
                'orders' => $orders
            ]);
        }else{
            return view($this->currentapplication.'.accountOrders', [
                'title' => 'Transaction record',
                'orders' => $orders
            ]);
        }


    }

    public function accountExpand(Application $wechat, $id) {

        $qrcode = $wechat->qrcode;
        $result = $qrcode->forever($id);
        $ticket = $result->ticket;

        if($this->currentapplication == "application"){
            return view($this->currentapplication.'.accountExpand', [
                'title' => '曼联国际运营中心',
                'qrcode' => $qrcode->url($ticket)
            ]);
        }else{
            return view($this->currentapplication.'.accountExpand', [
                'title' => 'Yangtze River International Commodity Trading Center',
                'qrcode' => $qrcode->url($ticket)
            ]);
        }


    }

    public function support(Application $wechat) {
        if($this->currentapplication == "application"){
            return view($this->currentapplication.'.support', [
                'title' => '在线咨询'
            ]);
        }else{
            return view($this->currentapplication.'.support', [
                'title' => 'Online consulting'
            ]);
        }

    }

    public function supportFaq(Application $wechat) {
        if($this->currentapplication == "application"){
            return view($this->currentapplication.'.supportFaq', [
                'title' => '常见问题'
            ]);
        }else{
            return view($this->currentapplication.'.supportFaq', [
                'title' => 'Common problems'
            ]);
        }

    }

    public function supportService(Application $wechat) {
        if($this->currentapplication == "application"){
            return view($this->currentapplication.'.supportService', [
                'title' => '在线客服'
            ]);
        }else{
            return view($this->currentapplication.'.supportService', [
                'title' => 'Online customer service'
            ]);
        }

    }

    public function supportFeedback(Request $request, Application $wechat) {

        if ($request->isMethod('post')) {

            if(!$request->input('content', null)
            || !$request->input('tool', null)
            || !$request->input('number', null)){
                if($this->currentapplication == "application"){
                    return view('application.info', [
                        'title' => '反馈失败',
                        'icon' => 'warn',
                        'content' => '请将表单填写完整，谢谢'
                    ]);
                }else{
                    return view('application.info', [
                        'title' => 'Feedback failure',
                        'icon' => 'warn',
                        'content' => 'Please fill in the form, thank you'
                    ]);
                }

            }

            $feedback = new Feedback;
            $feedback->body_content = $request->input('content');
            $feedback->body_tool = $request->input('tool');
            $feedback->body_number = $request->input('number');
            $feedback->save();

            if($this->currentapplication == "application"){
                return view($this->currentapplication.'.info', [
                    'title' => '反馈成功',
                    'icon' => 'success',
                    'content' => '我们已收到您的反馈，谢谢'
                ]);
            }else{
                return view($this->currentapplication.'.info', [
                    'title' => 'Feedback success',
                    'icon' => 'success',
                    'content' => 'We have received your feedback, thank you'
                ]);
            }

            
        } else {

            if($this->currentapplication == "application"){
                return view($this->currentapplication.'.supportFeedback', [
                    'title' => '意见反馈'
                ]);
            }else{
                return view($this->currentapplication.'.supportFeedback', [
                    'title' => 'Feedback'
                ]);
            }


        }

    }

}

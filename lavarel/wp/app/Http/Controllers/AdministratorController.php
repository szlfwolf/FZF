<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use App\Http\Models\Administrator;
use App\Http\Models\User;
use App\Http\Models\Order;
use App\Http\Models\Record;
use App\Http\Models\PayRequest;
use App\Http\Models\WithdrawRequest;
use App\Http\Models\Object;
use App\Http\Models\Feedback;
use App\Http\Models\Config;
use App\Http\Models\Logoninfo;
use Excel;
use Gregwar\Captcha\CaptchaBuilder;
class AdministratorController extends Controller {
	private function requiredSession(Request $request) {
		//判		断是否登录
		if(!$request->session()->has('administrator')){
			header('location: /administrator/signIn');
			exit();
		}
	}
	private function modifyEnv(array $data) {
		$envPath = base_path() . DIRECTORY_SEPARATOR . '.env';
		$contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));
		$contentArray->transform(function ($item) use ($data){
			foreach ($data as $key => $value){
				if(str_contains($item, $key)){
					return $key . '=' . $value;
				}
			}
			return $item;
		}
		);
		$content = implode($contentArray->toArray(), "\n");
		\File::put($envPath, $content);
	}
	///	/获得访客真实ip
	private  function Getip(){
		if(!empty($_SERVER["HTTP_CLIENT_IP"])){
			$cip = $_SERVER["HTTP_CLIENT_IP"];
		}
		elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
			$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		elseif(!empty($_SERVER["REMOTE_ADDR"])){
			$cip = $_SERVER["REMOTE_ADDR"];
		}
		else{
			$cip = "无法获取！";
		}
		return $cip;
	}
	///	/获得访客浏览器类型
	private function GetBrowser(){
		if(!empty($_SERVER['HTTP_USER_AGENT'])){
			$br = $_SERVER['HTTP_USER_AGENT'];
			if (preg_match('/MSIE/i',$br)) {
				$br = 'MSIE';
			}
			elseif (preg_match('/Firefox/i',$br)) {
				$br = 'Firefox';
			}
			elseif (preg_match('/Chrome/i',$br)) {
				$br = 'Chrome';
			}
			elseif (preg_match('/Safari/i',$br)) {
				$br = 'Safari';
			}
			elseif (preg_match('/Opera/i',$br)) {
				$br = 'Opera';
			}
			else {
				$br = 'Other';
			}
			return $br;
		}
		else{
			return "获取浏览器信息失败！";
		}
	}
	public function setting(Request $request){
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		$alert = null;
		if ($request->isMethod('post')) {
			$config = Config::find(1);
			$config->starttime = $request->input('starttime');
			$config->endtime = $request->input('endtime');
			$config->save();
			$alert = "保存成功";
		}
		$config = Config::find(1);
		$starttime = $config->starttime;
		$endtime = $config->endtime;
		return view('administrator.setting', [
		'active' => 'setting',
		'alert' => $alert,
		'starttime'=>$starttime,
		'endtime'=>$endtime,
		'type' =>$type
		]);
	}
	public function home(Request $request) {
		//判		断是否登录
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		//获		得id_agent;
		$agent = $request->session()->get('id_agent');
		//所		有用户都一个默认的id_agent 默认都是0 ，表示平台是最大的机构
		//判		断身份类型，如果type =0 表示是平台管理员，如果type = 1 表示是机构 ，则查询机构所属的用户
		if($type == 4){
			//会			员
			header('location: /administrator/mhome');
			exit();
		}
		else if($type == 1){
			//机			构
			header('location: /administrator/ahome');
			exit();
		}
		else{
			$query = '1=1';
			//表			示查询平台所有
		}
		$data = array(
		'today' => array(
		'users' => User::where('created_at', '>=', date('Y-m-d 00:00:00', time()))->whereRaw($query)->count(),
		'orders' => Order::where('created_at', '>=', date('Y-m-d 00:00:00', time()))->whereRaw($query)->count(),
		'payRequests' => PayRequest::where('processed_at', '<>', '0000-00-00 00:00:00')->where('created_at', '>=', date('Y-m-d 00:00:00', time()))->whereRaw($query)->sum('body_stake'),
		'withdrawRequests' => WithdrawRequest::where('created_at', '>=', date('Y-m-d 00:00:00', time()))->whereRaw($query)->sum('body_stake')
		),
		'count' => array(
		'day' => array(
		'stake' => floatval(Order::where('created_at', '>=', date('Y-m-d 00:00:00', time()))->sum('body_stake')) - floatval(Record::where('id_order', '<>', '0')->whereRaw($query)->where('body_direction', '1')->where('created_at', '>=', date('Y-m-d 00:00:00', time()))->sum('body_stake')),
		'free' => Record::where('created_at', '>=', date('Y-m-d 00:00:00', time()))->where('body_name', '註冊贈金')->whereRaw($query)->sum('body_stake'),
		'profit' => 0
		),
		'month' => array(
		'stake' => floatval(Order::where('created_at', '>=', date('Y-m-01 00:00:00', time()))->sum('body_stake')) - floatval(Record::where('id_order', '<>', '0')->whereRaw($query)->where('body_direction', '1')->where('created_at', '>=', date('Y-m-01 00:00:00', time()))->sum('body_stake')),
		'free' => Record::where('created_at', '>=', date('Y-m-01 00:00:00', time()))->where('body_name', '註冊贈金')->whereRaw($query)->sum('body_stake'),
		'profit' => 0
		),
		'all' => array(
		'payRequests' => PayRequest::where('processed_at', '<>', '0000-00-00 00:00:00')->whereRaw($query)->sum('body_stake'),
		'withdrawRequests' => WithdrawRequest::whereRaw($query)->sum('body_stake'),
		'balance' => User::sum('body_balance'),
		'free' => Record::where('body_name', '註冊贈金')->sum('body_stake'),
		'profit' => 0
		)
		)
		);
		$data['count']['day']['profit'] = floatval($data['count']['day']['stake']) - floatval($data['count']['day']['free']);
		$data['count']['month']['profit'] = floatval($data['count']['month']['stake']) - floatval($data['count']['month']['free']);
		$data['count']['all']['profit'] = floatval($data['count']['all']['payRequests']) - floatval($data['count']['all']['balance']) - floatval($data['count']['all']['withdrawRequests']);
		return view('administrator.home', [
		'active' => 'home',
		'data' => $data ,
		'type' =>$type
		]);
	}
	public function mhome(Request $request) {
		//判		断是否登录
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		//获		得id_agent;
		$id = $request->session()->get('administrator');
		//所		有用户都一个默认的id_agent 默认都是0 ，表示平台是最大的机构
		//判		断身份类型，如果type =0 表示是平台管理员，如果type = 1 表示是机构 ，则查询机构所属的用户
		$query = 'id_member = '.$id;
		$user = User::find($id);
		$data = array(
		'today' => array(
		'users' => User::where('created_at', '>=', date('Y-m-d 00:00:00', time()))->whereRaw($query)->count(),
		'orders' => Order::where('created_at', '>=', date('Y-m-d 00:00:00', time()))->whereRaw($query)->count(),
		'payRequests' => PayRequest::where('processed_at', '<>', '0000-00-00 00:00:00')->where('created_at', '>=', date('Y-m-d 00:00:00', time()))->whereRaw($query)->sum('body_stake'),
		'withdrawRequests' => WithdrawRequest::where('created_at', '>=', date('Y-m-d 00:00:00', time()))->whereRaw($query)->sum('body_stake')
		)   
		);
		return view('administrator.mhome', [
		'active' => 'home',
		'data' => $data ,
		'type' =>$type,
		'user'=>$user
		]);
	}
	public function ahome(Request $request) {
		//判		断是否登录
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		//获		得id_agent;
		$id = $request->session()->get('administrator');
		//所		有用户都一个默认的id_agent 默认都是0 ，表示平台是最大的机构
		//判		断身份类型，如果type =0 表示是平台管理员，如果type = 1 表示是机构 ，则查询机构所属的用户
		$query = 'id_agent = '.$id;
		$user = User::find($id);
		$data = array(
		'today' => array(
		'users' => User::where('created_at', '>=', date('Y-m-d 00:00:00', time()))->whereRaw($query)->count(),
		'orders' => Order::where('created_at', '>=', date('Y-m-d 00:00:00', time()))->whereRaw($query)->count(),
		'payRequests' => PayRequest::where('processed_at', '<>', '0000-00-00 00:00:00')->where('created_at', '>=', date('Y-m-d 00:00:00', time()))->whereRaw($query)->sum('body_stake'),
		'withdrawRequests' => WithdrawRequest::where('created_at', '>=', date('Y-m-d 00:00:00', time()))->whereRaw($query)->sum('body_stake')
		)   
		);
		return view('administrator.ahome', [
		'active' => 'home',
		'data' => $data ,
		'type' =>$type,
		'user'=>$user
		]);
	}
	public function users(Request $request) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		//获		得id_agent;
		$id_member = $request->session()->get('id_member');
		//所		有用户都一个默认的id_agent 默认都是0 ，表示平台是最大的机构
		//判		断身份类型，如果type =0 表示是平台管理员，如果type = 1 表示是机构 ，则查询机构所属的用户
		if($type == 4){
			//说			明是会员 
			$query = 'id_member = '.$id_member;
		}
		else if($type == 1){
			$query = 'id_agent = '.$id_member;
		}
		else{
			$query = '1=1';
			//表			示查询平台所有
		}
		//查		询所有普通用户列表 ，追上type= 3；
		$result = $query ." and  type=3";
		$datas = User::whereRaw($result)->orderBy('created_at', 'desc');
		if($request->input('id_user', null)) $datas->whereRaw($query)->where('id', $request->input('id_user'));
		if($request->input('body_phone', null)) $datas->whereRaw($query)->where('body_phone', $request->input('body_phone'));
		if($request->input('id_introducer', null)) $datas->whereRaw($query)->where('id_introducer', $request->input('id_introducer'));
		if($request->input('id_agent', null)) $datas->whereRaw($query)->where('id_agent', $request->input('id_agent'));
		if($request->input('id_member', null)) $datas->whereRaw($query)->where('id_member', $request->input('id_member'));
		$datas = $datas->paginate(20);
		return view('administrator.users', [
		'active' => 'users',
		'datas' => $datas,
		'id_user' => $request->input('id_user'),
		'type' =>$type
		]);
	}
	public function brokers(Request $request) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		//获		得id_agent;
		$id_member = $request->session()->get('id_member');
		//所		有用户都一个默认的id_agent 默认都是0 ，表示平台是最大的机构
		//判		断身份类型，如果type =0 表示是平台管理员，如果type = 1 表示是机构 ，则查询机构所属的用户
		if($type == 4){
			//说			明是机构
			$query = 'id_member = '.$id_member;
		}
		else if($type == 1){
			$query = 'id_agent = '.$id_member;
		}
		else{
			$query = '1=1';
			//表			示查询平台所有
		}
		//查		询所有经纪人列表 ，追上type= 2；
		$query = $query ." and  type=2";
		$datas = User::whereRaw($query)->orderBy('created_at', 'desc');
		if($request->input('id_user', null)) $datas->where('id', $request->input('id_user'));
		if($request->input('body_phone', null)) $datas->where('body_phone', $request->input('body_phone'));
		if($request->input('id_introducer', null)) $datas->where('id_introducer', $request->input('id_introducer'));
		$datas = $datas->paginate(20);
		return view('administrator.brokers', [
		'active' => 'brokers',
		'datas' => $datas,
		'id_user' => $request->input('id_user'),
		'type' =>$type
		]);
	}
	public function statusForUser(Request $request, $id) {
		$this->requiredSession($request);
		$user = User::find($id);
		if($user->is_disabled == 0) $user->is_disabled = 1;
		else $user->is_disabled = 0;
		$user->save();
		return '<script>alert("操作成功"); history.go(-1);</script>';
	}
	public function orders(Request $request) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		//获		得id_agent;
		$id_member = $request->session()->get('id_member');
		//所		有用户都一个默认的id_agent 默认都是0 ，表示平台是最大的机构
		//判		断身份类型，如果type =0 表示是平台管理员，如果type = 1 表示是机构 ，则查询机构所属的用户
		if($type == 4){
			//说			明是机构
			$query = 'id_member = '.$id_member;
		}
		else{
			$query = '1=1';
			//表			示查询平台所有
		}
		$datas = Order::whereRaw($query)->orderBy('created_at', 'desc');
		if($request->input('id_order', null)) $datas->where('id', $request->input('id_order'));
		if($request->input('id_user', null)) $datas->where('id_user', $request->input('id_user'));
		if($request->input('id_object', null)) $datas->where('id_object', $request->input('id_object'));
		$datas = $datas->paginate(20);
		return view('administrator.orders', [
		'active' => 'orders',
		'datas' => $datas,
		'id_user' => $request->input('id_user'),
		'id_object' => $request->input('id_object'),
		'type' =>$type
		]);
	}
	public function records(Request $request) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		//获		得id_agent;
		$id_member = $request->session()->get('id_member');
		//所		有用户都一个默认的id_agent 默认都是0 ，表示平台是最大的机构
		//判		断身份类型，如果type =0 表示是平台管理员，如果type = 1 表示是机构 ，则查询机构所属的用户
		if($type == 4){
			//说			明会员
			$query = 'id_member = '.$id_member;
		}
		else{
			$query = '1=1';
			//表			示查询平台所有
		}
		$datas = Record::whereRaw($query)->orderBy('created_at', 'desc');
		if($request->input('id_user', null)) $datas->where('id_user', $request->input('id_user'));
		$datas = $datas->paginate(20);
		return view('administrator.records', [
		'active' => 'records',
		'datas' => $datas,
		'id_user' => $request->input('id_user'),
		'type' =>$type
		]);
	}
	public function payRequests(Request $request) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		//获		得id_agent;
		$id_member = $request->session()->get('id_member');
		//所		有用户都一个默认的id_agent 默认都是0 ，表示平台是最大的机构
		//判		断身份类型，如果type =0 表示是平台管理员，如果type = 1 表示是机构 ，则查询机构所属的用户
		if($type == 4){
			//说			明是机构
			$query = 'id_member = '.$id_member;
		}
		else{
			$query = '1=1';
			//表			示查询平台所有
		}
		$datas = PayRequest::whereRaw($query)->orderBy('created_at', 'desc');
		if($request->input('id_payRequest', null)) $datas->where('id', $request->input('id_payRequest'));
		if($request->input('id_user', null)) $datas->where('id_user', $request->input('id_user'));
		$datas = $datas->paginate(20);
		return view('administrator.payRequests', [
		'active' => 'payRequests',
		'datas' => $datas,
		'id_user' => $request->input('id_user'),
		'type' =>$type
		]);
	}
	public function withholdForUser(Request $request, $id) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		$alert = NULL;
		if ($request->isMethod('post')) {
			if(!$request->input('stake', null) 
			|| !$request->input('transfer_number', null)){
				$alert = '参数提交不全';
			}
			else {
				if(intval($request->input('stake')) <=0){
					$alert = '扣款金额必须大于0元';
				}
				else {
					$user = User::find($id);
					$user->body_balance = $user->body_balance - intval($request->input('stake'));
					$user->save();
					$record = new Record;
					$record->id_user = $user->id;
					$record->body_name = $request->input('transfer_number');
					$record->body_direction = 0;
					$record->body_stake = intval($request->input('stake'));
					$record->id_agent = $user->id_agent;
					$record->save();
					$alert = '扣款成功';
				}
			}
		}
		return view('administrator.withholdForUser', [
		'active' => 'users',
		'id_user' => $id,
		'alert' => $alert,
		'type' =>$type
		]);
	}
	public function payForUser(Request $request, $id) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		$alert = NULL;
		if ($request->isMethod('post')) {
			if(!$request->input('stake', null) 
			|| !$request->input('transfer_number', null)){
				$alert = '参数提交不全';
			}
			else {
				if(intval($request->input('stake')) <=0){
					$alert = '充值金额必须大于0元';
				}
				else {
					$user = User::find($id);
					$payRequest = new payRequest;
					$payRequest->id_user = $id;
					$payRequest->body_stake = intval($request->input('stake'));
					$payRequest->body_gateway = 'staff';
					$payRequest->body_transfer_number = $request->input('transfer_number');
					$payRequest->processed_at = date('Y-m-d H:i:s', time());
					$payRequest->id_agent = $user->id_agent;
					$payRequest->save();
					$user = User::find($id);
					$user->body_balance = $user->body_balance + $payRequest->body_stake;
					$user->save();
					$record = new Record;
					$record->id_user = $user->id;
					$record->id_payRequest = $payRequest->id;
					$record->body_name = '帳戶充值';
					$record->body_direction = 1;
					$record->body_stake = $payRequest->body_stake;
					$record->id_agent =  $user->id_agent;
					$record->save();
					$alert = '充值成功';
				}
			}
		}
		return view('administrator.payForUser', [
		'active' => 'users',
		'id_user' => $id,
		'alert' => $alert,
		'type' => $type
		]);
	}
	//添	加会员用户方法
	public function addMember(Request $request) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		$alert = NULL;
		if ($request->isMethod('post')) {
			if(!$request->input('body_email', null) 
			|| !$request->input('body_password', null) 
			|| !$request->input('agent_code', null) 
			|| !$request->input('body_phone', null) 
			|| !$request->input('rate', null)
			|| !$request->input('fee', null)
			|| !$request->input('agent_name', null)){
				$alert = '参数提交不全';
			}
			else {
				$email = User::withTrashed()->where('body_email', $request->input('body_email'))->first();
				$id_agent = User::withTrashed()->where('agent_code', $request->input('agent_code'))->first();
				//先				查询 账号或者机构编码不能重复
				if($email || $id_agent){
					$alert = '会员账号或者会员推广码不能重复';
				}
				else{
					$agent = new User;
					$agent->type = 4;
					//表					示是会员身份，用户后台登录
					$agent->body_email = $request->input('body_email');
					$agent->body_password =  md5($request->input('body_password'));
					$agent->agent_code = $request->input('agent_code');
					$agent->body_phone = $request->input('body_phone');
					$agent->rate = $request->input('rate');
					$agent->fee = $request->input('fee');
					$agent->agent_name = $request->input('agent_name');
					$agent->save();
					$alert = '添加会员成功';
				}
			}
		}
		return view('administrator.addMember', [
		'active' => 'agent',
		'alert' => $alert,
		'type' => $type
		]);
	}
	//添	加机构用户方法
	public function addAgent(Request $request) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		//存		入库中
		$member_id = $request->session()->get('administrator');
		//只		有会员才能添加机构 
		if($type != 4){
			return ;
		}
		$member_id = $request->session()->get('administrator');
		$member = User::find($member_id);
		$member_code = $member->agent_code;
		$alert = NULL;
		if ($request->isMethod('post')) {
			if(!$request->input('body_password', null) 
			|| !$request->input('agent_code', null) 
			|| !$request->input('body_phone', null) 
			|| !$request->input('agent_name', null)){
				$alert = '参数提交不全';
			}
			else {
				$id_agent = User::withTrashed()->where('agent_code', $request->input('agent_code'))->first();
				//先				查询 账号或者机构编码不能重复
				if($id_agent){
					$alert = '机构推广码不能重复';
				}
				else{
					$agent = new User;
					$agent->type = 1;
					//表					示是机构身份，用户后台登录
					$agent->body_email = $request->input('member_code') . $request->input('agent_code');
					$agent->body_password =  md5($request->input('body_password'));
					$agent->agent_code = $request->input('member_code') . $request->input('agent_code');
					$agent->body_phone = $request->input('body_phone');
					$agent->rate = $request->input('rate');
					$agent->fee = $request->input('fee');
					$agent->id_member = $member_id;
					$agent->agent_name = $request->input('agent_name');
					$agent->save();
					$alert = '添加机构成功';
				}
			}
		}
		return view('administrator.addAgent', [
		'active' => 'agent',
		'alert' => $alert,
		'member_code' => $member_code,
		'type' => $type
		]);
	}
	//修	改会员用户方法
	public function updateMember(Request $request,$id) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		//只		有会员才能添加机构 
		if($type != 4){
			return ;
		}
		$member_id = $request->session()->get('administrator');
		$member = User::find($member_id);
		$member_code = $member->agent_code;
		$alert = NULL;
		if ($request->isMethod('post')) {
			if(!$request->input('body_phone', null) 
			|| !$request->input('rate', null)
			|| !$request->input('agent_name', null)){
				$alert = '参数提交不全';
			}
			else {
				$agent =User::find($request->input('id'));
				$agent->body_phone = $request->input('body_phone');
				$agent->rate = $request->input('rate');
				$agent->fee = $request->input('fee');
				$agent->agent_name = $request->input('agent_name');
				$agent->save();
				$alert = '更新会员信息成功';
			}
		}
		$agent = User::find($id);
		return view('administrator.updateMember', [
		'active' => 'agent',
		'alert' => $alert,
		'type' => $type,
		'member_code' => $member_code,
		'agent'=>$agent
		]);
	}
	//修	改机构用户方法
	public function updateAgent(Request $request,$id) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		$alert = NULL;
		if ($request->isMethod('post')) {
			if(!$request->input('body_phone', null) 
			|| !$request->input('agent_name', null)){
				$alert = '参数提交不全';
			}
			else {
				$agent =User::find($request->input('id'));
				$agent->body_phone = $request->input('body_phone');
				$agent->rate = $request->input('rate');
				$agent->fee = $request->input('fee');
				$agent->agent_name = $request->input('agent_name');
				$agent->save();
				$alert = '更新机构信息成功';
			}
		}
		$agent = User::find($id);
		return view('administrator.updateAgent', [
		'active' => 'agent',
		'alert' => $alert,
		'type' => $type,
		'agent'=>$agent
		]);
	}
	//修	改机构用户方法
	public function updateAgentPass(Request $request,$id) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		$alert = NULL;
		if ($request->isMethod('post')) {
			if(!$request->input('body_password', null) 
			|| !$request->input('id', null)){
				$alert = '参数提交不全';
			}
			else {
				$agent = User::find($request->input('id'));
				$agent->body_password = md5($request->input('body_password'));
				$agent->save();
				$alert = '修改密码成功';
			}
		}
		$agent = User::find($id);
		return view('administrator.updateAgentPass', [
		'active' => 'agent',
		'alert' => $alert,
		'type' => $type,
		'agent'=>$agent
		]);
	}
	//修	改会员密码方法
	public function updateMemberPass(Request $request,$id) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		$alert = NULL;
		if ($request->isMethod('post')) {
			if(!$request->input('body_password', null) 
			|| !$request->input('id', null)){
				$alert = '参数提交不全';
			}
			else {
				$agent = User::find($request->input('id'));
				$agent->body_password = md5($request->input('body_password'));
				$agent->save();
				$alert = '修改密码成功';
			}
		}
		$agent = User::find($id);
		return view('administrator.updateMemberPass', [
		'active' => 'agent',
		'alert' => $alert,
		'type' => $type,
		'agent'=>$agent
		]);
	}
	public function agentlist(Request $request) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		$id = $request->session()->get('id_member');
		$where = 'type = 1';
		if($type == 4){
			$where = 'type = 1 and id_member='.$id;
		}
		$datas = User::whereRaw($where)->orderBy('created_at', 'desc')->paginate(20);
		return view('administrator.agentlist', [
		'active' => 'administrators',
		'datas' => $datas,
		'type'=>$type
		]);
	}
	public function memberlist(Request $request) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		$where = 'type = 4';
		$datas = User::whereRaw($where)->orderBy('created_at', 'desc')->paginate(20);
		return view('administrator.memberlist', [
		'active' => 'administrators',
		'datas' => $datas,
		'type'=>$type
		]);
	}
	// 	删除机构的操作
	public function agentdel(Request $request, $id) {
		$this->requiredSession($request);
		$agent = User::find($id);
		$agent->delete();
		return '<script>alert("操作成功"); window.location.href="/administrator/agentlist";</script>';
	}
	// 	删除会员的操作
	public function memberdel(Request $request, $id) {
		$this->requiredSession($request);
		$agent = User::find($id);
		$agent->delete();
		return '<script>alert("操作成功"); window.location.href="/administrator/memberlist";</script>';
	}
	public function withdrawForUser(Request $request, $id) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		$alert = NULL;
		$withdrawRequest = WithdrawRequest::find($id);
		if ($request->isMethod('post') && $withdrawRequest->processed_at == '0000-00-00 00:00:00') {
			if(!$request->input('transfer_number', null)){
				$alert = '参数提交不全';
			}
			else {
				$withdrawRequest->body_transfer_number = $request->input('transfer_number');
				$withdrawRequest->processed_at = date('Y-m-d H:i:s', time());
				$withdrawRequest->save();
				$alert = '处理完毕';
			}
		}
		return view('administrator.withdrawForUser', [
		'active' => 'withdrawRequests',
		'alert' => $alert,
		'id' => $id,
		'transfer_number' => $withdrawRequest->body_transfer_number,
		'processed_at' => $withdrawRequest->processed_at,
		'type' => $type
		]);
	}
	public function withdrawForUserCanceled(Request $request, $id) {
		$this->requiredSession($request);
		$withdrawRequest = WithdrawRequest::find($id);
		if ($withdrawRequest->processed_at == '0000-00-00 00:00:00') {
			$withdrawRequest->body_transfer_number = 'FAIL';
			$withdrawRequest->processed_at = date('Y-m-d H:i:s', time());
			$withdrawRequest->save();
			$user = User::find($withdrawRequest->id_user);
			$user->body_balance = $user->body_balance + $withdrawRequest->body_stake;
			$user->save();
			$record = new Record;
			$record->id_user = $user->id;
			$record->id_withdrawRequest = $withdrawRequest->id;
			$record->body_name = '提现退回';
			$record->body_direction = 1;
			$record->id_agent = $user->id_agent;
			$record->body_stake = $withdrawRequest->body_stake;
			$record->save();
		}
		return redirect('/administrator/withdrawRequests');
	}
	public function withdrawRequests(Request $request) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		//获		得id_agent;
		$agent = $request->session()->get('id_agent');
		//所		有用户都一个默认的id_agent 默认都是0 ，表示平台是最大的机构
		//判		断身份类型，如果type =0 表示是平台管理员，如果type = 1 表示是机构 ，则查询机构所属的用户
		if($type == 1){
			//说			明是机构
			$query = 'id_agent = '.$agent;
		}
		else{
			$query = 'id_agent >= 0';
			//表			示查询平台所有
		}
		$datas = WithdrawRequest::whereRaw($query)->orderBy('created_at', 'desc');
		if($request->input('id_withdrawRequest', null)) $datas->where('id', $request->input('id_withdrawRequest'));
		if($request->input('id_user', null)) $datas->where('id_user', $request->input('id_user'));
		$datas = $datas->paginate(20);
		return view('administrator.withdrawRequests', [
		'active' => 'withdrawRequests',
		'datas' => $datas,
		'id_user' => $request->input('id_user'),
		'type' =>$type
		]);
	}
	public function objects(Request $request) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		$datas = Object::orderBy('created_at', 'desc')->paginate(20);
		return view('administrator.objects', [
		'active' => 'objects',
		'datas' => $datas,
		'type' => $type
		]);
	}
	public function feedbacks(Request $request) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		$datas = Feedback::orderBy('created_at', 'desc')->paginate(20);
		return view('administrator.feedbacks', [
		'active' => 'feedbacks',
		'datas' => $datas,
		'type' => $type
		]);
	}
	public function administrators(Request $request) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		$datas = Administrator::orderBy('created_at', 'desc')->paginate(20);
		return view('administrator.administrators', [
		'active' => 'administrators',
		'datas' => $datas,
		'type'=>$type
		]);
	}
	public function signIn(Request $request) {
		if($request->session()->get('administrator')){
			return redirect('/administrator');
		}
		if ($request->isMethod('post')) {
			$userInput = $request->get('captcha');
			//$builder = new CaptchaBuilder;
		    if (env("capcha") && Session::get('milkcaptcha') != $userInput  ){
			//if(!$builder->testPhrase($userInput)) {
				return view('administrator.signIn', [
				'title' => '登录失败',
				'icon' => 'warn',
				'content' => '您输入验证码错误'
				]);
				//用				户输入验证码错误
				return '您输入验证码错误';
			}
			$administrator = User::where('body_email', $request->input('email'))->where('body_password', md5($request->input('password')))->first();
			if($administrator){
				$request->session()->put('administrator', $administrator->id);
				$request->session()->put('type', $administrator->type);
				$request->session()->put('id_member', $administrator->id);
				//
				$administrator->updated_at = date('Y-m-d H:i:s');
				$administrator->save();
				/* 记				录登录信息
				$record = new Logoninfo;
				$record->id_user = $administrator->id;
				$record->cip =  $this->Getip();
				$record->agent_name = $administrator->agent_name;
				$record->from = $this->GetBrowser();
				$record->online = "Y";
				$record->save();
				*/
				return redirect('/administrator');
			}
			else{
				return view('administrator.signIn', [
				'title' => '登录失败',
				'icon' => 'warn',
				'content' => '用户名或者密码错误'
				]);
			}
		}
		return view('administrator.signIn',[
		'captchaurl' => 'xxx'
		]);
	}
	public function signOut(Request $request) {
		$id = $request->session()->get('id_member');
		$request->session()->forget('administrator');
		//记		录登录信息
		Logoninfo::where('id_user',$id)->update(['online' => 'N']);
		return redirect('/administrator/signIn');
	}
	//统	计信息
	public function mem_orders_count(Request $request){
		//查		询所属
		//没		有查询条件 ，则查询所有订单
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		$members =User::whereRaw('type=4')->get();
		$starttime = $request->input('starttime');
		$endtime = $request->input('endtime');
		$query = "1=1";
		if(!empty($starttime) && empty($endtime)){
			$query = "created_at >= '".$starttime."'";
		}
		if(empty($starttime) && !empty($endtime)){
			$query = "created_at <= '".$endtime."'";
		}
		if(!empty($starttime) && !empty($endtime)){
			$query = "created_at >= '".$starttime."' and created_at <='".$endtime."'";
		}
		$datas = Order::whereRaw($query)->orderBy('created_at', 'desc');
		if($request->input('id_member', null)) $datas->where('id_member', $request->input('id_member'));
		$datas = $datas->paginate(20);
		return view('administrator.mem_orders_count', [
		'active' => 'orders',
		'datas' => $datas,
		'members' => $members,
		'type' =>$type
		]);
	}
	public function mem_orders_count_ex(Request $request){
		//没		有查询条件 ，则查询所有订单
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		$starttime = $request->input('starttime');
		$endtime = $request->input('endtime');
		$query = "1=1";
		if(!empty($starttime) && empty($endtime)){
			$query = "created_at >= '".$starttime."'";
		}
		if(empty($starttime) && !empty($endtime)){
			$query = "created_at <= '".$endtime."'";
		}
		if(!empty($starttime) && !empty($endtime)){
			$query = "created_at >= '".$starttime."' and created_at <='".$endtime."'";
		}
		$datas = Order::whereRaw($query)->orderBy('created_at', 'desc');
		if($request->input('id_member', null)) $datas->where('id_member', $request->input('id_member'));
		$datas = $datas->get();
		$result = array(
		array(
		'用户编号',
		'订单编号',
		'买入金额',
		'盈亏',
		'手续费',
		'余额',
		'时间'
		)
		);
		foreach ($datas as $item) {
			$win_money = 0;
			if($item->body_is_win == 1){
				$win_money = $item->body_bonus;
			}
			if($item->body_is_draw == 1){
				$win_money = 0;
			}
			if($item->body_is_draw != 1 && $item->body_is_win == 0){
				$win_money = $item->body_stake;
			}
			$result[] = array(
			$item->id_user."(".$item->user->body_phone.")",
			$item->id,
			$item->body_stake,
			$win_money,
			$item->body_fee,
			$item->body_balance,
			$item->created_at
			);
		}
		Excel::create('Orders', function($excel) use($result) {
			$excel->sheet('Datas', function($sheet) use($result) {
				$sheet->fromArray($result);
			}
			);
		}
		)->export('xls');
	}
	//统	计信息
	public function ag_orders_count(Request $request){
		//查		询所属
		//没		有查询条件 ，则查询所有订单
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		$raw = "1=1";
		if($type == 4){
			$id =  $request->session()->get('id_member');
			$raw = "id_member=".$id;
		}
		$agents =User::whereRaw($raw)->where('type',1)->get();
		$query = "1=1";
		if(!empty($starttime) && empty($endtime)){
			$query = "created_at >= '".$starttime."'";
		}
		if(empty($starttime) && !empty($endtime)){
			$query = "created_at <= '".$endtime."'";
		}
		if(!empty($starttime) && !empty($endtime)){
			$query = "created_at >= '".$starttime."' and created_at <='".$endtime."'";
		}
		$query = $raw ." and ".$query;
		$datas = Order::whereRaw($query)->orderBy('created_at', 'desc');
		if($request->input('id_agent', null)) $datas->where('id_agent', $request->input('id_agent'));
		$datas = $datas->paginate(20);
		return view('administrator.ag_orders_count', [
		'active' => 'orders',
		'datas' => $datas,
		'members' => $agents,
		'type' =>$type
		]);
	}
	public function ag_orders_count_ex(Request $request){
		//没		有查询条件 ，则查询所有订单
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		$id =  $request->session()->get('id_member');
		$starttime = $request->input('starttime');
		$endtime = $request->input('endtime');
		$query = "1=1";
		if(!empty($starttime) && empty($endtime)){
			$query = "created_at >= '".$starttime."'";
		}
		if(empty($starttime) && !empty($endtime)){
			$query = "created_at <= '".$endtime."'";
		}
		if(!empty($starttime) && !empty($endtime)){
			$query = "created_at >= '".$starttime."' and created_at <='".$endtime."'";
		}
		if($type ==4){
			$query = $query ." and id_member=".$id;
		}
		$query = $raw ." and ".$query;
		$datas = Order::whereRaw($query)->orderBy('created_at', 'desc');
		if($request->input('id_agent', null)) $datas->where('id_agent', $request->input('id_agent'));
		$datas = $datas->get();
		$result = array(
		array(
		'用户编号',
		'订单编号',
		'买入金额',
		'盈亏',
		'手续费',
		'余额',
		'时间'
		)
		);
		foreach ($datas as $item) {
			$win_money = 0;
			if($item->body_is_win == 1){
				$win_money = $item->body_bonus;
			}
			if($item->body_is_draw == 1){
				$win_money = 0;
			}
			if($item->body_is_draw != 1 && $item->body_is_win == 0){
				$win_money = $item->body_stake;
			}
			$result[] = array(
			$item->id_user."(".$item->user->body_phone.")",
			$item->id,
			$item->body_stake,
			$win_money,
			$item->body_fee,
			$item->body_balance,
			$item->created_at
			);
		}
		Excel::create('Orders', function($excel) use($result) {
			$excel->sheet('Datas', function($sheet) use($result) {
				$sheet->fromArray($result);
			}
			);
		}
		)->export('xls');
	}
	//统	计信息
	public function br_orders_count(Request $request){
		//查		询所属
		//没		有查询条件 ，则查询所有订单
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		$raw = "1=1";
		if($type == 1){
			$id =  $request->session()->get('id_member');
			$raw = "id_agent=".$id;
		}
		if($type == 4){
			$id =  $request->session()->get('id_member');
			$raw = "id_member=".$id;
		}
		$brokers =User::whereRaw($raw)->where('type',2)->get();
		$query = "1=1";
		if(!empty($starttime) && empty($endtime)){
			$query = "created_at >= '".$starttime."'";
		}
		if(empty($starttime) && !empty($endtime)){
			$query = "created_at <= '".$endtime."'";
		}
		if(!empty($starttime) && !empty($endtime)){
			$query = "created_at >= '".$starttime."' and created_at <='".$endtime."'";
		}
		$query = $raw ." and ".$query;
		$datas = Order::whereRaw($query)->orderBy('created_at', 'desc');
		if($request->input('id_broker', null)) $datas->where('id_broker', $request->input('id_broker'));
		$datas = $datas->paginate(20);
		return view('administrator.br_orders_count', [
		'active' => 'orders',
		'datas' => $datas,
		'members' => $brokers,
		'type' =>$type
		]);
	}
	public function br_orders_count_ex(Request $request){
		//没		有查询条件 ，则查询所有订单
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		$id =  $request->session()->get('id_member');
		$starttime = $request->input('starttime');
		$endtime = $request->input('endtime');
		$query = "1=1";
		if(!empty($starttime) && empty($endtime)){
			$query = "created_at >= '".$starttime."'";
		}
		if(empty($starttime) && !empty($endtime)){
			$query = "created_at <= '".$endtime."'";
		}
		if(!empty($starttime) && !empty($endtime)){
			$query = "created_at >= '".$starttime."' and created_at <='".$endtime."'";
		}
		if($type == 1){
			$query = $query ." and id_agent=".$id;
		}
		else if($type == 4){
			$query = $query ." and id_member=".$id;
		}
		$datas = Order::whereRaw($query)->orderBy('created_at', 'desc');
		if($request->input('id_broker', null)) $datas->where('id_broker', $request->input('id_broker'));
		$datas = $datas->get();
		$result = array(
		array(
		'用户编号',
		'订单编号',
		'买入金额',
		'盈亏',
		'手续费',
		'余额',
		'时间'
		)
		);
		foreach ($datas as $item) {
			$win_money = 0;
			if($item->body_is_win == 1){
				$win_money = $item->body_bonus;
			}
			if($item->body_is_draw == 1){
				$win_money = 0;
			}
			if($item->body_is_draw != 1 && $item->body_is_win == 0){
				$win_money = $item->body_stake;
			}
			$result[] = array(
			$item->id_user."(".$item->user->body_phone.")",
			$item->id,
			$item->body_stake,
			$win_money,
			$item->body_fee,
			$item->body_balance,
			$item->created_at
			);
		}
		Excel::create('Orders', function($excel) use($result) {
			$excel->sheet('Datas', function($sheet) use($result) {
				$sheet->fromArray($result);
			}
			);
		}
		)->export('xls');
	}
	//机	构申请提现
	public function agentWithdraw(Request $request) {
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		// 		获得登录机构的id
		$id = $request->session()->get('administrator');
		$alert = NULL;
		$user = User::find($id);
		if(empty($user) || $user->is_disabled > 0) return $this->denialUser();
		if ($request->isMethod('post')) {
			if(!$request->input('name', null)
			|| !$request->input('number', null)
			|| !$request->input('bank', null)
			|| !$request->input('deposit', null)
			|| !$request->input('stake', null)){
				$alert = "請將表單填寫完整，謝謝";
			}
			if(intval($request->input('stake', 0)) < 100) {
				$alert = "單次提現金額不得低於 100 元";
			}
			if(intval($request->input('stake', 0)) > intval($user->body_balance)) {
				$alert = "您當前的帳戶餘額不足";
			}
			if(intval($request->input('stake', 0)) % 100 != 0) {
				$alert = "提现金额必须为 100 元的倍数";
			}
			$orderSum = Order::where('id_user', $user->id)->sum('body_stake');
			if(intval($orderSum) < 300){
				$alert = "为避免恶意透支，累积交易金额超过 300 元即可提现";
			}
			DB::beginTransaction();
			$user->body_balance = $user->body_balance - $request->input('stake');
			$user->save();
			if($user->body_balance < 0) {
				DB::rollback();
			}
			else {
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
				$record->body_name = '結餘提現';
				$record->body_direction = 0;
				$record->body_stake = $withdrawRequest->body_stake;
				$record->id_agent = $user->agent_code;
				$record->save();
			}
			DB::commit();
			$alert = "申请成功";
		}
		return view('administrator.agentWithDraw', [
		'active' => 'agentWithDraw',
		'alert' => $alert,
		'user' => $user,
		'type' => $type
		]);
	}
	public function usersExport(Request $request) {
		$this->requiredSession($request);
	}
	public function ordersExport(Request $request) {
		$this->requiredSession($request);
		$result = array(
		array(
		'订单编号',
		'用户',
		'交易标的',
		'买入价格',
		'买入金额',
		'买入方向',
		'买入时长',
		'买入时间',
		'结算价格',
		'结算结果',
		'结算时间',
		'订单调控'
		)
		);
		$datas = Order::all();
		foreach ($datas as $item) {
			if($item->body_direction == 1) $direction_name = '看涨';
			else $direction_name = '看跌';
			$result_name = '亏损';
			if($item->body_is_draw == 1) $result_name = '平局';
			if($item->body_is_win == 1) $result_name = '盈利';
			$controlled_name = '否';
			if($item->body_is_controlled == 1) $controlled_name = '是';
			$result[] = array(
			$item->id,
			$item->user->body_phone,
			$item->object->body_name,
			$item->body_price_buying,
			$item->body_stake,
			$direction_name,
			$item->body_time,
			$item->created_at,
			$item->body_price_striked,
			$result_name,
			$item->striked_at,
			$controlled_name
			);
		}
		Excel::create('Orders', function($excel) use($result) {
			$excel->sheet('Datas', function($sheet) use($result) {
				$sheet->fromArray($result);
			}
			);
		}
		)->export('xls');
	}
	public function recordsExport(Request $request) {
		$this->requiredSession($request);
		$result = array(
		array(
		'记录编号',
		'用户',
		'关联用户',
		'关联充值',
		'关联提现',
		'变动缘由',
		'变动方向',
		'变动金额',
		'变动时间'
		)
		);
		$datas = Record::all();
		foreach ($datas as $item) {
			if($item->body_direction == 1) $direction_name = '收入';
			else $direction_name = '支出';
			$result[] = array(
			$item->id,
			$item->user->body_phone,
			$item->id_refer,
			$item->id_payRequest,
			$item->id_withdrawRequest,
			$item->body_name,
			$direction_name,
			$item->body_stake,
			$item->created_at
			);
		}
		Excel::create('Records', function($excel) use($result) {
			$excel->sheet('Datas', function($sheet) use($result) {
				$sheet->fromArray($result);
			}
			);
		}
		)->export('xls');
	}
	public function agent_recordsExport(Request $request,$id) {
		$this->requiredSession($request);
		$result = array(
		array(
		'记录编号',
		'用户',
		'关联用户',
		'关联充值',
		'关联提现',
		'变动缘由',
		'变动方向',
		'变动金额',
		'变动时间'
		)
		);
		$datas = Record::whereRaw("id_user=".$id);
		foreach ($datas as $item) {
			if($item->body_direction == 1) $direction_name = '收入';
			else $direction_name = '支出';
			$result[] = array(
			$item->id,
			$item->user->body_phone,
			$item->id_refer,
			$item->id_payRequest,
			$item->id_withdrawRequest,
			$item->body_name,
			$direction_name,
			$item->body_stake,
			$item->created_at
			);
		}
		Excel::create('Records', function($excel) use($result) {
			$excel->sheet('Datas', function($sheet) use($result) {
				$sheet->fromArray($result);
			}
			);
		}
		)->export('xls');
	}
	public function payRequestsExport(Request $request) {
		$this->requiredSession($request);
		$result = array(
		array(
		'充值编号',
		'用户',
		'金额',
		'充值方式',
		'流水编号',
		'申请时间',
		'入账时间'
		)
		);
		$datas = PayRequest::all();
		foreach ($datas as $item) {
			$gateway_name = '未知';
			if($item->body_gateway == 'wechat') $gateway_name = '微信支付';
			if($item->body_gateway == 'union') $gateway_name = '银联支付';
			if($item->body_gateway == 'staff') $gateway_name = '人工充值';
			$result[] = array(
			$item->id,
			$item->user->body_phone,
			$item->body_stake,
			$gateway_name,
			$item->body_transfer_number,
			$item->created_at,
			$item->processed_at
			);
		}
		Excel::create('PayRequests', function($excel) use($result) {
			$excel->sheet('Datas', function($sheet) use($result) {
				$sheet->fromArray($result);
			}
			);
		}
		)->export('xls');
	}
	public function withdrawRequestsExport(Request $request) {
		$this->requiredSession($request);
		$result = array(
		array(
		'提现编号',
		'用户',
		'金额',
		'开户银行',
		'开户名称',
		'开户网点',
		'开户帐号',
		'流水编号',
		'申请时间',
		'处理时间'
		)
		);
		$datas = WithdrawRequest::all();
		foreach ($datas as $item) {
			$bank_name = '未知';
			if($item->body_bank == 'ccb') $bank_name = '建设银行';
			if($item->body_bank == 'icbc') $bank_name = '工商银行';
			if($item->body_bank == 'boc') $bank_name = '中国银行';
			if($item->body_bank == 'abc') $bank_name = '农业银行';
			if($item->body_bank == 'comm') $bank_name = '交通银行';
			if($item->body_bank == 'spdb') $bank_name = '浦发银行';
			if($item->body_bank == 'ecb') $bank_name = '光大银行';
			if($item->body_bank == 'cmbc') $bank_name = '民生银行';
			if($item->body_bank == 'cib') $bank_name = '兴业银行';
			if($item->body_bank == 'cmb') $bank_name = '招商银行';
			if($item->body_bank == 'psbc') $bank_name = '邮政储蓄';
			$result[] = array(
			$item->id,
			$item->user->body_phone,
			$item->body_stake,
			$bank_name,
			$item->body_name,
			$item->body_deposit,
			$item->body_number,
			$item->body_transfer_number,
			$item->created_at,
			$item->processed_at
			);
		}
		Excel::create('WithdrawRequests', function($excel) use($result) {
			$excel->sheet('Datas', function($sheet) use($result) {
				$sheet->fromArray($result);
			}
			);
		}
		)->export('xls');
	}
	public function orderWillWin(Request $request) {
		if (env('ORDER_WILL_WIN')) {
			$this->modifyEnv([
			'ORDER_WILL_WIN' => 0,
			'ORDER_WILL_LOST' => 0
			]);
		}
		else {
			$this->modifyEnv([
			'ORDER_WILL_WIN' => 1,
			'ORDER_WILL_LOST' => 0
			]);
		}
		return back()->withInput();
	}
	public function orderWillLost(Request $request) {
		if (env('ORDER_WILL_LOST')) {
			$this->modifyEnv([
			'ORDER_WILL_LOST' => 0,
			'ORDER_WILL_WIN' => 0
			]);
		}
		else {
			$this->modifyEnv([
			'ORDER_WILL_LOST' => 1,
			'ORDER_WILL_WIN' => 0
			]);
		}
		return back()->withInput();
	}
	public function orderControl(Request $request) {
		if (env('ORDER_CONTROL')) {
			$this->modifyEnv([
			'ORDER_CONTROL' => 0
			]);
		}
		else {
			$this->modifyEnv([
			'ORDER_CONTROL' => 1
			]);
		}
		return back()->withInput();
	}
	//管	理员修改密码
	public function changePass(Request $request){
		$this->requiredSession($request);
		//取		得身份类型
		$type = $request->session()->get('type');
		$id = $request->session()->get('id_member');
		$alert = null;
		if ($request->isMethod('post')) {
			if(!$request->input('oldpass', null) 
			|| !$request->input('newpass', null)){
				$alert = '参数提交不全';
			}
			else {
				$user = User::find($id);
				if($user->body_password == md5($request->input('oldpass'))){
					$user->body_password = md5($request->input('newpass'));
					$user->save();
					$alert = '密码修改成功';
				}
				else{
					$alert = '原密码输入错误';
				}
			}
		}
		return view('administrator.changePass', [
		'active' => 'agent',
		'alert' => $alert,
		'type' => $type,
		]);
	}

}

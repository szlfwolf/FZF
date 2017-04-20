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

class ReportController extends Controller {
	private function requiredSession(Request $request) {
		// 判 断是否登录
		if (! $request->session ()->has ( 'administrator' )) {
			header ( 'location: /administrator/signIn' );
			exit ();
		}
	}
	
	public function report_fund_user(Request $request) {
		$this->requiredSession ( $request );
		$type = $request->session ()->get ( 'type' );		
		$sdate = $request->input('start_date',date("Y-m-d",strtotime("-100 day")) );
		$edate = $request->input('end_date', date("Y-m-d"));		
		$datas = DB::select ( 'SELECT a.id_user,max(c.id_name) id_name,
				max(d.agent_name) agent_name,
				max(e.agent_name) member_name,
				max(c.body_phone) body_phone,
count(*) total,
sum(body_is_win) win_total,
sum(body_is_draw) draw_total,
sum(body_stake) volume,
sum(case when body_is_win =1 then body_stake else 0 end) win_volume,
sum(case when body_is_draw =1 then body_stake else 0 end) draw_volume,
sum(case when body_is_win =1 then (a.body_bonus -a.body_fee)  else 0 end) win_amount,
sum(case when body_is_win =0 then (a.body_bonus -a.body_fee)  else 0 end) lose_amount,
sum(body_fee) fee_total
FROM `orders` a
left join `users` c on a.id_user = c.id
left join `users` d on c.id_agent = d.id
left join `users` e on c.id_member = e.id
where striked_at between :start_date and :end_date 
group by a.id_user', [
		'start_date' =>  $sdate,
		'end_date'   => $edate
] );					
		//$datas = $datas->paginate(20);
		return view ( 'administrator.report_fund_user', [ 
				'active' => 'report_fund_user',
				'datas' => $datas,		
				'sdate' => $sdate,
				'edate' => $edate,
				'type' => $type 
		] );
	}
	
	public function report_fund_agent(Request $request) {
		$this->requiredSession ( $request );
		$type = $request->session ()->get ( 'type' );
		$sdate = $request->input('start_date',date("Y-m-d",strtotime("-100 day")) );
		$edate = $request->input('end_date', date("Y-m-d"));
		$datas = DB::select ( 'SELECT c.id_name,c.agent_code,
				max(c.agent_name) agent_name,
count(*) total,
sum(body_is_win) win_total,
sum(body_is_draw) draw_total,
sum(body_stake) volume,
sum(case when body_is_win =1 then body_stake else 0 end) win_volume,
sum(case when body_is_draw =1 then body_stake else 0 end) draw_volume,
sum(case when body_is_win =1 then (a.body_bonus -a.body_fee)  else 0 end) win_amount,
sum(case when body_is_win =0 then (a.body_bonus -a.body_fee)  else 0 end) lose_amount,
sum(body_fee) fee_total
FROM `orders` a
left join `users` c on a.id_agent = c.id
where striked_at between :start_date and :end_date
group by c.id_name,c.agent_code', [
			'start_date' =>  $sdate,
			'end_date'   => $edate
	] );
		//$datas = $datas->paginate(20);
		return view ( 'administrator.report_fund_agent', [
				'active' => 'report_fund_agent',
				'datas' => $datas,
				'sdate' => $sdate,
				'edate' => $edate,
				'type' => $type
		] );
	}
	
	public function report_fund_member(Request $request) {
		$this->requiredSession ( $request );
		$type = $request->session ()->get ( 'type' );		
		$sdate = $request->input('start_date',date("Y-m-d",strtotime("-100 day")) );
		$edate = $request->input('end_date', date("Y-m-d"));		
		$datas = DB::select ( 'SELECT c.id_name,
count(*) total,
sum(body_is_win) win_total,
sum(body_is_draw) draw_total,
sum(body_stake) volume,
sum(case when body_is_win =1 then body_stake else 0 end) win_volume,
sum(case when body_is_draw =1 then body_stake else 0 end) draw_volume,
sum(case when body_is_win =1 then (a.body_bonus -a.body_fee)  else 0 end) win_amount,
sum(case when body_is_win =0 then (a.body_bonus -a.body_fee)  else 0 end) lose_amount,
sum(body_fee) fee_total
FROM `orders` a
left join `users` c on a.id_member = c.id
where striked_at between :start_date and :end_date 
group by c.id_name', [
		'start_date' =>  $sdate,
		'end_date'   => $edate
] );					
		//$datas = $datas->paginate(20);
		return view ( 'administrator.report_fund_member', [ 
				'active' => 'report_fund_member',
				'datas' => $datas,		
				'sdate' => $sdate,
				'edate' => $edate,
				'type' => $type 
		] );
	}


	public function report_deal(Request $request) {
		$this->requiredSession ( $request );
		$type = $request->session ()->get ( 'type' );		
		$sdate = $request->input('start_date',date("Y-m-d",strtotime("-100 day")) );
		$edate = $request->input('end_date', date("Y-m-d"));		
		$datas = DB::select ( 'SELECT b.body_name,a.id_member,a.id_object,c.id_name,
count(*) total,
sum(body_is_win) win_total,
sum(body_is_draw) draw_total,
sum(body_stake) volume,
sum(case when body_is_win =1 then body_stake else 0 end) win_volume,
sum(case when body_is_draw =1 then body_stake else 0 end) draw_volume,
sum(case when body_is_win =1 then (a.body_bonus -a.body_fee)  else 0 end) win_amount,
sum(case when body_is_win =0 then (a.body_bonus -a.body_fee)  else 0 end) lose_amount,
sum(body_fee) fee_total
FROM `orders` a
left join `objects` b on a.id_object = b.id
left join `users` c on a.id_member = c.id
where striked_at between :start_date and :end_date
group by b.body_name,a.id_member,a.id_object,c.id_name', [
		'start_date' =>  $sdate,
		'end_date'   => $edate
] );					
		//$datas = $datas->paginate(20);
		return view ( 'administrator.report_deal', [ 
				'active' => 'report_deal',
				'datas' => $datas,		
				'sdate' => $sdate,
				'edate' => $edate,
				'type' => $type 
		] );
	}
	

	public function stat_orders(Request $request) {
		$this->requiredSession ( $request );
		$type = $request->session ()->get ( 'type' );
		$sdate = $request->input('start_date',date("Y-m-d",strtotime("-100 day")) );
		$edate = $request->input('end_date', date("Y-m-d"));
		
		$query = "";
		//$query = "striked_at between ". $sdate." and ". $edate. " and ";
		
		if($type == 4){
			//说			明是机构
			$query = $query. 'id_member = '.$id_member;
		}
		else{
			$query =  '1=1';
			//表			示查询平台所有
		}
		$datas = Order::where('striked_at', '>', $sdate)->where('striked_at', '<', $edate) ->whereRaw($query)->orderBy('created_at', 'desc');
		if($request->input('id_order', null)) $datas->where('id', $request->input('id_order'));
		if($request->input('id_user', null)) $datas->where('id_user', $request->input('id_user'));
		if($request->input('id_object', null)) $datas->where('id_object', $request->input('id_object'));
		$datas = $datas->paginate(20);
		
		return view ( 'administrator.stat_orders', [
				'active' => 'stat_orders',
				'datas' => $datas,
				'sdate' => $sdate,
				'edate' => $edate,
				'id_user' => $request->input('id_user'),
				'id_object' => $request->input('id_object'),
				'type' => $type
		] );
	}
	
	public function stat_records(Request $request) {
		$this->requiredSession($request);
		$sdate = $request->input('start_date',date("Y-m-d",strtotime("-100 day")) );
		$edate = $request->input('end_date', date("Y-m-d"));
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
		$datas = Record::where('striked_at', '>', $sdate)->where('striked_at', '<', $edate) ->whereRaw($query)->orderBy('created_at', 'desc');
		if($request->input('id_user', null)) $datas->where('id_user', $request->input('id_user'));
		$datas = $datas->paginate(20);
		return view('administrator.stat_records', [
		'active' => 'stat_records',
				'sdate' => $sdate,
				'edate' => $edate,
		'datas' => $datas,
		'id_user' => $request->input('id_user'),
		'type' =>$type
		]);
	}
}
?>
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
	public function report_fund_member(Request $request) {
		$this->requiredSession($request);
		$datas = Logoninfo::where('online','Y')->orderBy('created_at', 'desc');
		$datas = $datas->paginate(20);
		//取		得身份类型
		$type = $request->session()->get('type');
		return view('administrator.monitor_online_user', [
				'active' => 'monitor_online_user',
				'datas' => $datas,
				'type' => $type
		]);
	}
	public function report_fund_user(Request $request) {
		$this->requiredSession($request);
		$datas = Logoninfo::where('online','Y')->orderBy('created_at', 'desc');
		$datas = $datas->paginate(20);
		//取		得身份类型
		$type = $request->session()->get('type');
		return view('administrator.monitor_online_user', [
				'active' => 'monitor_online_user',
				'datas' => $datas,
				'type' => $type
		]);
	}
	public function report_fund_agent(Request $request) {
		$this->requiredSession($request);
		$datas = Logoninfo::where('online','Y')->orderBy('created_at', 'desc');
		$datas = $datas->paginate(20);
		//取		得身份类型
		$type = $request->session()->get('type');
		return view('administrator.monitor_online_user', [
				'active' => 'monitor_online_user',
				'datas' => $datas,
				'type' => $type
		]);
	}
	public function report_deal_member(Request $request) {
		

		
		$this->requiredSession ( $request );
		$type = $request->session ()->get ( 'type' );		

		$sdate = date("Y-m-d",strtotime("-100 day"));
		$edate = date("Y-m-d");

		
		
		$datas = DB::select ( 'SELECT b.body_name,a.id_member,a.id_object,c.agent_name,
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
group by id_member,id_object,b.body_name', [
		'start_date' => '2017-01-01',
		'end_date'   => '2017-05-01'
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
}
?>
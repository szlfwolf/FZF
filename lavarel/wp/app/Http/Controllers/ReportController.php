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
		//判		断是否登录
		if(!$request->session()->has('administrator')){
			header('location: /administrator/signIn');
			exit();
		}
	}

	public function report_fund_member(Request $request){
		$this->requiredSession($request);
		$type = $request->session()->get('type');
		
		$datas = User::where('type',$usertype)->orderBy('created_at', 'desc');
		$datas = $datas->paginate(20);
		return view('administrator.report_fund',[
				'active' =>'report_fund_member',
				'datas' => $datas,
				'type' =>  $type
		]);
	}

	public function report_fund_user(Request $request){
		$this->requiredSession($request);
		$type = $request->session()->get('type');
	
		$datas = User::where('type',$usertype)->orderBy('created_at', 'desc');
		$datas = $datas->paginate(20);
		return view('administrator.report_fund',[
				'active' =>'report_fund_user',
				'datas' => $datas,
				'type' =>  $type
		]);
	}
	
	public function report_fund_agent(Request $request){
		$this->requiredSession($request);
		$type = $request->session()->get('type');
	
		$datas = User::where('type',$usertype)->orderBy('created_at', 'desc');
		$datas = $datas->paginate(20);
		return view('administrator.report_fund',[
				'active' =>'report_fund_agent',
				'datas' => $datas,
				'type' =>  $type
		]);
	}
	
	public function report_deal_member(Request $request){
		$this->requiredSession($request);
		$type = $request->session()->get('type');
	
		$datas = User::where('type',$type)->orderBy('created_at', 'desc');
		$datas = $datas->paginate(20);
		return view('administrator.report_deal',[
				'active' =>'report_deal',
				'datas' => $datas,
				'type' =>  $type
		]);
	}



}
?>
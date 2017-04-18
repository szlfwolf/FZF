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


class MonitorController extends Controller {
  	private function requiredSession(Request $request) {
		//判		断是否登录
		if(!$request->session()->has('administrator')){
			header('location: /administrator/signIn');
			exit();
		}
	}
  //后台-实时监控-在线用户
  public function online_user(Request $request){
		$this->requiredSession($request);
		$datas = Logoninfo::where('online','Y')->orderBy('created_at', 'desc');
		$datas = $datas->paginate(20);
		//取		得身份类型
		$type = $request->session()->get('type');
		return view('administrator.monitor.online_user', [
		'active' => 'online_user',
		'datas' => $datas,
		'type' => $type
		]);
	}
 //后台-实时监控-客户持仓监控
 public function positioncontrol(Request $request){
    $this->requiredSession($request);
    return view('administrator.positioncontrol',[
      'active' => 'online_user',
		  'datas' => $datas,
		  'type' =>  $request->session()->get('type')
    ]);
  }
  //后台-实时监控-会员保证金监控
  public function sbondcontrol(Request $request){
    $this->requiredSession($request);
    $datas = User::where('type',4)->orderBy('created_at', 'desc');
    $datas = $datas->paginate(20);
    return view('administrator.sbondcontrol',[
      'active' => 'sbondcontrol',
		  'datas' => $datas,
		  'type' =>  $request->session()->get('type')
    ]);
  }


}
?>
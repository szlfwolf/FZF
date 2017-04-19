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
		return view('administrator.monitor_online_user', [
		'active' => 'monitor_online_user',
		'datas' => $datas,
		'type' => $type
		]);
	}
 //后台-实时监控-客户持仓监控
 public function positioncontrol(Request $request){
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
    
    $query .=  " AND DATE_ADD(created_at,INTERVAL body_time SECOND) <  now() AND striked_at is null ";
    $datas = Order::whereRaw($query)->orderBy('created_at', 'desc');
    if($request->input('id_order', null)) $datas->where('id', $request->input('id_order'));
    if($request->input('id_user', null)) $datas->where('id_user', $request->input('id_user'));
    if($request->input('id_object', null)) $datas->where('id_object', $request->input('id_object'));
    $datas = $datas->paginate(20);
    return view('administrator.monitor_positioncontrol', [
    		'active' => 'monitor_positioncontrol',
    		'datas' => $datas,
    		'id_user' => $request->input('id_user'),
    		'id_object' => $request->input('id_object'),
    		'type' =>$type
    ]);
  }
  //后台-实时监控-会员保证金监控
  public function sbondcontrol(Request $request){
    $this->requiredSession($request);
    $datas = User::where('type',4)->orderBy('created_at', 'desc');
    $datas = $datas->paginate(20);
    return view('administrator.monitor_sbondcontrol',[
      'active' => 'monitor_sbondcontrol',
		  'datas' => $datas,
		  'type' =>  $request->session()->get('type')
    ]);
  }


}
?>
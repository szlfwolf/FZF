<?php 
 
namespace App\Http\Models;
//引入软删除
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class User extends Model {

	protected $fillable = [];


	public static $rules = [

	];

	    //开启如删除
    use SoftDeletes;
    //数据表中的删除字段
    protected $dates = ['deleted_at'];

}

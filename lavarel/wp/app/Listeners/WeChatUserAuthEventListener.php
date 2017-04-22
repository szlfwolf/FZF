<?php

namespace App\Listeners;

use Overtrue\LaravelWechat\Events\WeChatUserAuthorized;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Models\User;

class WeChatUserAuthEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  WeChatUserAuthorized  $event
     * @return void
     */
    public function handle(WeChatUserAuthorized $event)
    {
    	$user = $event->user;
    	if( $event->isNewSession ){
    		
    		$user = User::where('id_wechat', $user->id)->first();
    		if($user){
    		$user->updated_at = date('Y-m-d H:i:s');
    		$user->save();
    		}
    	}
        
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\SocialLoginServiceInterface;
use App\User;
use App\UserDetail;
use App\UserSetting;
use Auth;

class FacebookLoginController extends Controller
{
    public function login(SocialLoginServiceInterface $facebook) {

    	$profile_data = $facebook->getUserData();
    	if ($profile_data) {
    		$user = User::where('facebook_id', $profile_data['id'])
    			->where('email', $profile_data['email'])
    			->first();
    		if (!$user) {
    			$user = User::create([
    				'email' => $profile_data['email'],
    				'password' => '',
    				'name' => $profile_data['name'],
    				'facebook_id' => $profile_data['id'],
                	'photo' => $profile_data['data']['url'],
    				'setup_step' => 1
    			]);

    			// note: maybe put this in model event for user creation to prevent repetition
    			UserDetail::create([
            		'user_id' => $user->id
        		]);

		        UserSetting::create([
		            'user_id' => $user->id,
		            'is_orders_notification_enabled' => false,
		            'is_bid_notification_enabled' => false,
		            'is_bid_accepted_notification_enabled' => false
		        ]);
    		}

    		Auth::loginUsingId($user->id);
    		return redirect('/');
    	}
    	
    	return back()->with('alert', ['type' => 'danger', 'text' => "Sorry. Something went wrong while trying to log you in. Please try again."]);
    }
}

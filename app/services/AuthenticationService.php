<?php
namespace app\services;



use DB;
use Carbon\Carbon;

class AuthenticationService 
{
    /**
     * verify user
     * @param  [array] $token [description]
     * @param  [user] $user  [description]
     * @param  array  $input [description]
     * @return [array]        [token]
     */
    public function verify($token, $user) 
    {
        $token = $this->setExpireTime($user,$token);
        return $token;
        // $this->saveDomain($user, $input);
        // $this->checkDevice($user, $token, $input);
        // $this->checkStatus($user, $token);//check whether user active or not..

        
    }

    private function setExpireTime($user, $token) 
    {
        // if(($user->company_id != Config::get('jp.demo_subscriber_id')) || (!$user->isStandardUser())) return $token;
        $expireTime = Carbon::now()->addSeconds(30000)->timestamp;
        DB::table('oauth_access_tokens')->where('id',$token['access_token'])->update(['expire_time' => $expireTime]);
        $token['expires_in'] = 30000;
        return $token;
    }
}
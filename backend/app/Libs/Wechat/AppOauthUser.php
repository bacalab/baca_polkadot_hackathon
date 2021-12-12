<?php

namespace App\Libs\Wechat;


use Overtrue\LaravelWeChat\Facade as EasyWechat;

class AppOauthUser extends SocialAccountOauthUser
{
    public function __construct()
    {
        $this->app = EasyWechat::officialAccount();
        parent::__construct();
    }
}

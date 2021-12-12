<?php

namespace App\Libs\Wechat;

use Overtrue\Socialite\User;

abstract class SocialAccountOauthUser
{
    protected $app;
    /** @var User */
    protected $oauthUser;

    public function __construct()
    {
        $this->oauthUser = $this->getOauthUser();
    }

    public function getOauthUser()
    {
        if (!$oauthUser = $this->app->oauth->user()) {
            abort(403, '登陆失败，请重试');
        }

        return $oauthUser;
    }

    public function getName()
    {
        return $this->oauthUser->getName();
    }

    public function getAvatar()
    {
        return $this->oauthUser->getAvatar();
    }

    public function getOriginal()
    {
        return $this->oauthUser->getOriginal();
    }
}

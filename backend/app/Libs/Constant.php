<?php


namespace App\Libs;


class Constant
{
    const REDIS_INSTANCE_USER_AUTHORIZE = 'users-authorize';

    const POLKA_TOKEN_BALANCE = "http://ec2-35-77-14-64.ap-northeast-1.compute.amazonaws.com:3000/api/ballance";
    const POLKA_TOKEN_REWARD = "http://ec2-35-77-14-64.ap-northeast-1.compute.amazonaws.com:3000/api/get_reward";

    /**
     * 缓存 passport 用户信息 key
     * @param $uid
     * @return string
     */
    public static function passportUserCacheKey($uid)
    {
        return sprintf("passport:user:%s", $uid);
    }

    /**
     * 缓存 passport 用户 token key
     * @param $id
     * @return string
     */
    public static function passportTokenCacheKey($id)
    {
        return sprintf("passport:token:%s", $id);
    }

    /**
     * 用户信息缓存
     * @param $uid
     * @return string
     */
    public static function userInfo($uid)
    {
        return sprintf("user:%s", $uid);
    }

    public static function redpackOpen($rid, $uid)
    {
        return sprintf('redpack:id:%s:uid:%s', $rid, $uid);
    }
}

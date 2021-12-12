<?php

namespace App\Models;

use App\Libs\Constant;
use Illuminate\Support\Arr;

class UserCache extends ModelBase
{

    private $redisClient;
    private $userKey;

    public function getRedis()
    {
        if (!$this->redisClient) {
            $this->redisClient = redis('users');
        }
        return $this->redisClient;
    }

    public function getUserKey($uid)
    {
        if (!$this->userKey) {
            $this->userKey = Constant::userInfo($uid);
        }
        return $this->userKey;
    }

    /**
     * 设置用户表单个用户信息到缓存
     * @param array $user
     * @return bool
     */
    public function setUserAllInfoCache(array $user)
    {
        if (empty($user) || !isset($user['id']) || $user['id'] <= 0) {
            return false;
        }
        $key = $this->getUserKey($user['id']);
        $this->getRedis()->hmset($key, $user);
        $this->getRedis()->expire($key, 86400 * 10);
        return true;
    }

    /**
     * 获取用户表单个用户所有缓存
     * @param $uid
     * @return array
     */
    public function getUserAllInfoCache($uid)
    {
        if ($uid <= 0) {
            return [];
        }

        $key = $this->getUserKey($uid);
        $userInfo = $this->getRedis()->hgetall($key);
        if (!$userInfo) {
            $user = User::query()->where(['id' => $uid])->first();
            if ($user) {
                $user = $user->toArray();
                $this->setUserAllInfoCache($user);
                $userInfo = $this->getRedis()->hgetall($key);
            }
        }
        return $userInfo;
    }

    /**
     * 根据key设置用户缓存信息
     * @param $uid
     * @param $key
     * @param $value
     * @return bool
     */
    public function setUserCache($uid, $key, $value)
    {
        if (!$uid || !$key) {
            return false;
        }

        $hashKey = $this->getUserKey($uid);
        return $this->getRedis()->hset($hashKey, $key, $value);
    }

    /**
     * 用户缓存key + addCount
     * @param $uid
     * @param $key
     * @param $addCount
     * @return bool|int
     */
    public function incrUserCache($uid, $key, $addCount)
    {
        if (!$uid || !$key) {
            return false;
        }

        $hashKey = $this->getUserKey($uid);
        return $this->getRedis()->hincrby($hashKey, $key, $addCount);
    }

    /**
     * 根据key获取用户缓存信息
     * @param $uid
     * @param $key
     * @return string
     */
    public function getUserCache($uid, $key)
    {
        if (!$uid || !$key) {
            return '';
        }

        $hashKey = $this->getUserKey($uid);
        $value = $this->getRedis()->hget($hashKey, $key);
        if (!$value) {
            $allInfo = $this->getUserAllInfoCache($uid);
            $value = Arr::get($allInfo, $key, null);
        }
        return $value;
    }

}

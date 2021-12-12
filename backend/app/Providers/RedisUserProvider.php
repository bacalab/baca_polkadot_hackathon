<?php


namespace App\Providers;


use App\Libs\Constant;
use App\Models\User;
use Illuminate\Auth\EloquentUserProvider;

class RedisUserProvider extends EloquentUserProvider
{
    public function retrieveById($identifier)
    {
        if (empty($identifier)) {
            return null;
        }
        $user = null;
        $switch = config('app.cache_auth');
        $key = Constant::passportUserCacheKey($identifier);
        if ($switch) {
            $res = redis(Constant::REDIS_INSTANCE_USER_AUTHORIZE)->get($key);
            $res && ($user = unserialize($res));
        }
        if (!($user instanceof User)) {
            /** @var User $user */
            $user = User::query()->find($identifier);
            if ($switch) {
                User::cacheUserAuthInfo($user);
            }
        }

        if (empty($user)) {
            return null;
        }
        return $user;
    }
}

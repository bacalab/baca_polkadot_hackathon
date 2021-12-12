<?php


namespace App\Providers;


use App\Libs\Constant;
use Laravel\Passport\Token;

class PassportTokenCacheModelProvider extends Token
{
    private $id;

    public function where($field, $id)
    {
        if ('id' == $field) {
            $this->id = $id;
        }
        return $this;
    }

    public function first()
    {
        if ($this->id) {
            $id = $this->id;
            $token = null;
            $key = Constant::passportTokenCacheKey($this->id);
            $switch = config('app.cache_auth');
            if ($switch) {
                $res = redis(Constant::REDIS_INSTANCE_USER_AUTHORIZE)->get($key);
                $res && ($token = unserialize($res));
            }
            if (!($token instanceof Token)) {
                $token = Token::query()->where('id', $id)->first();
                if ($switch) {
                    redis(Constant::REDIS_INSTANCE_USER_AUTHORIZE)->set($key, serialize($token));
                    redis(Constant::REDIS_INSTANCE_USER_AUTHORIZE)->expire($key, 3600);
                }
            }
            return $token;
        }
        return null;
    }
}

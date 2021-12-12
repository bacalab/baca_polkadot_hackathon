<?php

namespace App\Models;

use App\Libs\Constant;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->invite_code = static::generateInviteCode();
            if (!$user->invite_code) {
                return false;
            }
            return true;
        });

        // 创建新用户时，建立用户缓存
        static::created(function (User $user) {
            if ($user) {
                $userArr = $user->toArray();
                (new UserCache())->setUserAllInfoCache($userArr);
            }
        });

        // 更新用户信息时，重新设置数据库对应字段的缓存
        static::saved(function (User $user) {
            if ($user) {
                static::cacheUserAuthInfo($user->id);
                $userArr = $user->withoutRelations()->toArray();
                (new UserCache())->setUserAllInfoCache($userArr);
            }
        });
    }

    /**
     * 清除用户的登录 token
     */
    public function clearToken()
    {
        $list = DB::table('oauth_access_tokens')->where(['user_id' => $this->id])->get();
        foreach ($list as $item) {
            $key = Constant::passportTokenCacheKey($item->id);
            redis(Constant::REDIS_INSTANCE_USER_AUTHORIZE)->del([$key]);
        }
        DB::table('oauth_access_tokens')->where(['user_id' => $this->id])->delete();
        $key = Constant::passportUserCacheKey($this->id);
        redis(Constant::REDIS_INSTANCE_USER_AUTHORIZE)->del([$key]);
    }

    /**
     * 重新缓存用户的 auth 信息
     * @param User|int $user 参数必须是 用户 model 或者 用户 uid
     */
    public static function cacheUserAuthInfo($user)
    {
        if (config('app.cache_auth')) {
            if (!($user instanceof User) && is_numeric($user)) {
                $user = self::query()->find($user);
            }
            if (empty($user)) {
                return;
            }
            $key = Constant::passportUserCacheKey($user->id);
            redis(Constant::REDIS_INSTANCE_USER_AUTHORIZE)->del([$key]);
            redis(Constant::REDIS_INSTANCE_USER_AUTHORIZE)->set($key, serialize($user));
            redis(Constant::REDIS_INSTANCE_USER_AUTHORIZE)->expire($key, 300);
        }
    }


    /**
     * 生成邀请码
     * @return string
     */
    public static function generateInviteCode()
    {
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $index = rand(0, 25);
        $prefix = substr($str, $index, 1);
        $lastUser = static::query()->orderByDesc('id')->first();
        $id = 1;
        if ($lastUser) {
            $id += $lastUser->id;
        }
        $len = strlen($id);
        $min = pow(10, (7 - $len - 1));
        $max = pow(10, (7 - $len)) - 1;
        $rand = rand($min, $max);
        return $prefix . $id . $rand;
    }

    /**
     * 设置备注信息
     * @param $key
     * @param string $val
     * @return bool
     */
    public function setMemo($key, $val = '')
    {
        $memo = $this->memo;
        $memoArr = [];
        if (!empty($memo)) {
            $memoArr = json_decode($memo, true);
            if (!is_array($memoArr)) {
                $memoArr = [];
            }
        }
        $memoArr[$key] = $val;
        $this->memo = json_encode($memoArr);
        return $this->save();

    }

    /**
     * 获取MEMO里对应KEY的值
     * @param $key
     * @return mixed|string
     */
    public function getMemo($key)
    {
        $memo = $this->memo;
        if (!empty($memo)) {
            $memoArr = json_decode($memo, true);
            if (!is_array($memoArr)) {
                $memoArr = [];
            }
            return $memoArr[$key] ?? '';
        }
        return '';
    }

}

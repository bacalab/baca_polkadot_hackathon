<?php


namespace App\Http\Controllers;


use App\Libs\Output;
use App\Models\User;
use Illuminate\Http\Request;

class ToolController
{
    /**
     * 手动登录用户
     * @param $id
     * @return array
     */
    public function index($id)
    {
        if (app()->environment('production')) {
            return [];
        }
        /** @var User $user */
        $user = User::query()->where(["unionid" => $id])->first();
        if (!$user) {
            $headId = rand(1, 9);
            $data = [
                "device" => "",
                "unionid" => $id,
                "invite_code" => User::generateInviteCode(),
                "name" => $id,
                "headimgurl" => "http://www.bacamedium.com/static/images/avatar/{$headId}.png"
            ];
            $user = User::query()->create($data);
        }
//        $user = User::query()->findOrFail($id);
        $user->clearToken();
        $token = $user->createToken('app')->accessToken;

        return Output::ret(Output::SUCCEED, ['token' => $token]);
    }

    /**
     * 获取当前用户登录信息
     * @return array
     */
    public function test()
    {
        if (app()->environment('production')) {
            return [];
        }
        /** @var User $user */
        $user = auth()->user();
        return Output::ret(Output::SUCCEED, [$user->toArray()]);
    }

    /**
     * 解析 post 参数
     * @param Request $request
     * @return array
     */
    public function decryptData(Request $request)
    {
        if (app()->environment('production')) {
            return Output::ret(Output::SUCCEED, ['msg' => '线上不可访问']);
        }
        return Output::ret(Output::SUCCEED, $request->post());
    }
}

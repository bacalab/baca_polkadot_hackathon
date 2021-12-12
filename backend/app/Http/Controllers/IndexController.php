<?php


namespace App\Http\Controllers;


use App\Libs\Output;
use App\Models\Subscribe;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function ping()
    {
        return Output::ret(Output::SUCCEED);
    }

    public function abort(Request $request)
    {
        switch ($request->get("code")) {
            case Output::OP_ACCESS_DENIED:
                return Output::ret(Output::OP_ACCESS_DENIED);
            case Output::OP_DATA_NOT_EXISTS:
                return Output::ret(Output::OP_DATA_NOT_EXISTS);
            default:
                return Output::ret(Output::FAILED);
        }
    }

    public function noAuth()
    {
        return Output::ret(Output::AUTH_FAILED_LOGIN, '请重新登录');
    }

    /**
     * 用户订阅
     * @param Request $request
     * @return array
     */
    public function subscribe(Request $request)
    {
        $email = $request->post("email");
        if (preg_match("/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/", $email, $match)) {
            $checkExists = Subscribe::query()->where(["email"=>$email])->first();
            if (!$checkExists) {
                $data = ["email"=>$email];
                Subscribe::query()->create($data);
                return Output::ret(Output::SUCCEED);
            } else {
                return Output::ret(Output::OP_DATA_EXISTS);
            }
        }

        return Output::ret(Output::FAILED);
    }
}

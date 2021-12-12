<?php

namespace App\Http\Controllers;

use App\Libs\Constant;
use App\Libs\Output;
use App\Models\User;
use App\Models\VoteRecord;
use App\Services\UserService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class UserController extends Controller
{

    function info(Request $request)
    {
        $user = auth()->user();
        $uid = 100;
        $user = User::query()->find($uid);
        $data = $user->toArray();
        $data["stake"] = UserService::stakeTotal($uid);
        $data["total"] = $data['stake'] + $data["money"];

        return Output::ret(Output::SUCCEED, $data);
    }

    function tokenBalance(Request $request)
    {
        $address = $request->get("address", "");
        try {
            $user = auth()->user();
            if (empty($address) && $user) {
                $address = $user->getMemo("polka_address");
            }
            $client = new Client(['timeout' => 2.0]);
            $url = sprintf(Constant::POLKA_TOKEN_BALANCE, $address);
            $res = $client->request('GET', $url, [
                'query' => ['addr' => $address]
            ]);

            $resArr = json_decode($res->getBody(), true);
            if (!json_last_error()) {
                return Output::ret(Output::SUCCEED, $resArr);
            }
        } catch (\Exception $e) {
            logger()->error("UserController:tokenBalance", ["err" => $e->getMessage()]);
        }

        return Output::ret(Output::FAILED);
    }

    function saveStake(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            goto FAIL;
        }
        $stake = $request->get("stake");
        $stake = intval($stake);
        $aid = $request->get("aid");

        $address = $user->unionid;
        $balance = UserService::polkaBalance($address);
        if ($stake > $balance) {
            return Output::ret(Output::WITHDRAW_LOSS_MONEY);
        }

        $vote = VoteRecord::query()->create([
            "user_id" => $user->id ?? 100,
            "stake" => intval($stake),
            "aid" => intval($aid)
        ]);

        if ($vote) {
            return Output::ret(Output::SUCCEED);
        }

        FAIL:
        return Output::ret(Output::FAILED);
    }

    function stakeList(Request $request)
    {
        $user = auth()->user();

        $list = VoteRecord::query()
            ->with("article")
            ->where(["user_id" => $user->id ?? 100])->orderByDesc("id")->limit(20)->get();

        if ($list) {
            return Output::ret(Output::SUCCEED, $list->toArray());
        }

        return Output::ret(Output::FAILED);
    }
}

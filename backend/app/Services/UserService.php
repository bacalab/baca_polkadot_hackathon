<?php
/**
 * Created by PhpStorm.
 * User: anwang
 * Date: 2021-12-08
 * Time: 22:42
 */

namespace App\Services;


use App\Libs\Constant;
use App\Models\VoteRecord;
use GuzzleHttp\Client;

class UserService
{

    static function polkaBalance($address)
    {
        $client = new Client(['timeout' => 2.0]);
        $url = sprintf(Constant::POLKA_TOKEN_BALANCE, $address);
        $res = $client->request('GET', $url, [
            'query' => ['addr' => $address]
        ]);

        $resArr = json_decode($res->getBody(), true);
        if (!json_last_error()) {
            return $resArr["ballance"] ?? 0;
        }
        return 0;
    }

    static function stakeTotal($uid)
    {
        return VoteRecord::query()->where(["user_id"=>$uid])->sum("stake");
    }
}

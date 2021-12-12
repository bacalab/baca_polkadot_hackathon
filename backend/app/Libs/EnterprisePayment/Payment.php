<?php

namespace App\Libs\EnterprisePayment;

use Overtrue\LaravelWeChat\Facade;

class Payment
{
    const FORCE_CHECK_USER_NAME = 'FORCE_CHECK';
    const NO_CHECK_USER_NAME    = 'NO_CHECK';

    private $app;

    public function __construct($channel = 'default')
    {
        $this->app = Facade::payment($channel);
    }

    /**
     * @param string $target 提现用户的 openID
     * @param int $amount 提现金额，单位为分
     * @param string $no 提现水单号
     * @param string $desc 提现描述
     * @param bool $checkName 是否验证用户姓名
     * @param string $realUserName 用户的真实姓名
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function transfer($target, $amount, $no, $desc = '提现', $checkName = false, $realUserName = '')
    {
        $data = [
            'partner_trade_no' => $no,
            'openid'           => $target,
            'check_name'       => $checkName ? self::FORCE_CHECK_USER_NAME : self::NO_CHECK_USER_NAME,
            're_user_name'     => $realUserName,
            'amount'           => $amount,
            'desc'             => $desc,
        ];
        try {
            $res = $this->app->transfer->toBalance($data);
            logger()->info('[withdraw]', ['param' => $data, 'res' => $res]);
        } catch (\Exception $e) {
            logger()->error('[withdrawErr]', [$e->getCode(), $e->getMessage()]);
            $res = ['return_code' => 'FAIL', 'return_msg' => '提现异常' . $e->getMessage()];
        }
        return $res;
    }

    /**
     * 查询提现单号
     * @param $no
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function searchBalance($no)
    {
        try {
            $res = $this->app->transfer->queryBalanceOrder($no);
            logger()->info('[withdrawSearch]', $res);
        } catch (\Exception $e) {
            logger()->error('[withdrawSearchErr]', [$e->getCode(), $e->getMessage()]);
            $res = ['return_code' => 'FAIL', 'return_msg' => '查询异常' . $e->getMessage()];
        }
        return $res;
    }
}

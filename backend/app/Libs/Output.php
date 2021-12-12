<?php


namespace App\Libs;


class Output
{
    private static $structure = null;

    const SUCCEED = 0; //成功
    const FAILED = -1; //失败
    const AUTH_FAILED_LOGIN = -1000; // token 失效，重新登录
    const AUTH_FAILED_IMEI_ALREADY_REGISTERED = -1002; // 注册失败,该设备已经注册过
    const IGNORE = -2000; // 客户端 忽略返回

    const OP_ACCESS_DENIED = -6001; //无权操作
    const OP_DATA_NOT_EXISTS = -6002;
    const OP_TOO_MANY = -6003;
    const OP_DATA_EXISTS = -6004;

    const WITHDRAW_FAILED = -4000; // 提现失败
    const WITHDRAW_LOSS_MONEY = -4001; // 余额不足


    static $return = [
        self::SUCCEED => "成功",
        self::FAILED => "网络异常，请下拉刷新",
        self::AUTH_FAILED_IMEI_ALREADY_REGISTERED => "注册失败,该设备已经注册过",
        // 这种状态下，客户端不作处理
        self::IGNORE => '忽略返回',

        self::OP_ACCESS_DENIED => "无权操作",
        self::OP_DATA_NOT_EXISTS => "数据不存在",
        self::OP_TOO_MANY => "操作太频繁",
        self::OP_DATA_EXISTS => "数据已存在",
        self::WITHDRAW_LOSS_MONEY => "余额不足",
    ];

    /**
     * 构建输出结构
     * @param null $structure
     */
    public static function dataStruct($structure = null)
    {
        if (is_string($structure)) {
            $structure = [$structure];
        }
        if (is_array($structure)) {
            self::$structure = $structure;
        }
    }

    /**
     * 获取接口返回参数
     * 第一个参数是code 必选， 第二个及以后可以为多个参数
     * 总共有三个参数时， 第二个为message，第三个为接口传出的 data
     * 总共有二个参数时， 当code有设置时， 第二个参数为data, 当code没有设置时第二个参数为message
     * @param int $code
     * @param mixed ...$param
     * @return array
     */
    public static function ret(int $code, ...$param)
    {
        if (sizeof($param) >= 2) {
            $msg = $param[0];
            $data = $param[1];
            !is_null(self::$structure) && $data = dataFormat(self::$structure, $data);

            return ['code' => $code, 'message' => $msg, 'data' => $data];
        } elseif (sizeof($param) == 1) {
            $data = $param[0];
            !is_null(self::$structure) && $data = dataFormat(self::$structure, $data);

            if (isset(self::$return[$code])) {
                return ['code' => $code, 'message' => self::$return[$code], 'data' => $data];
            }
            return ['code' => $code, 'message' => $param[0]];
        } else {

            if (isset(self::$return[$code])) {
                return ['code' => $code, 'message' => self::$return[$code]];
            }
            return ['code' => $code, 'message' => ""];
        }
    }
}

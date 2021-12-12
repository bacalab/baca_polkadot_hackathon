<?php

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Predis\Client;

if (!function_exists('genBigIntId')) {
    /**
     *
     * @return int
     */
    function genBigIntId()
    {
        return (int)(time() . substr(microtime(), 2, 6) . rand(100, 999));
    }
}

if (!function_exists('miniUserName')) {
    /**
     * 简化用户名
     * @param $name
     * @return string
     */
    function miniUserName($name)
    {
        if (mb_strlen($name) > 4) {
            return mb_substr($name, 0, 4) . '...';
        }
        return $name;
    }
}

if (!function_exists('redis')) {
    /**
     * 连接 redis 的客户端
     * @param string $connection
     * @return Client
     */
    function redis($connection = 'default')
    {
        $conf = config('database.redis');
        $param = \Illuminate\Support\Arr::get($conf, $connection);
        $options = \Illuminate\Support\Arr::get($conf, 'options');
        $redisClient = new Client($param, $options);
        return $redisClient;
    }
}

if (!function_exists('isLocked')) {
    /**
     * 判断是否被锁定：true 锁定中；false 未锁定
     * @param $key
     * @param $timeout
     * @return bool
     */
    function isLocked($key, $timeout = 1)
    {
        $res = redis()->set($key, 1, 'EX', $timeout, 'NX');
        return !$res;
    }
}

if (!function_exists('userDefaultHeadImg')) {
    /**
     * 用户默认头像
     * @return string
     */
    function userDefaultHeadImg()
    {
        return 'https://static1.haohuimai1.com/image/fake_user/' . rand(1300, 2300) . '.png';
    }
}

if (!function_exists('getImei')) {
    /**
     * @return array|string|null
     */
    function getImei()
    {
        return request()->header('imei');
    }
}

if (!function_exists('getLocalImei')) {
    /**
     * @param bool $localFirst
     * @return array|string|null
     */
    function getLocalImei($localFirst = true)
    {
        $imei = $localFirst ? request()->header('local') : null;
        if (empty($imei)) {
            $imei = getImeiReal();
            if ($imei == '0000') {
                if (!$localFirst) {
                    $imei = request()->header('local');
                } else {
                    $imei = uniqid();
                }
            }
        }
        return $imei;
    }
}

if (!function_exists('getDeviceID')) {
    /**
     * @return array|string|null
     */
    function getDeviceID()
    {
        return getLocalImei();
    }
}

if (!function_exists('getImeiReal')) {
    /**
     * @return array|string|null
     */
    function getImeiReal()
    {
        return request()->header('imei-real');
    }
}

if (!function_exists('getOaid')) {
    /**
     * @return array|string|null
     */
    function getOaid()
    {
        return request()->header('oaid');
    }
}

if (!function_exists('getAndroidId')) {
    /**
     * @return array|string|null
     */
    function getAndroidId()
    {
        return request()->header('android-id');
    }
}

if (!function_exists('getIdfa')) {
    /**
     * @return array|string|null
     */
    function getIdfa()
    {
        return request()->header('idfa');
    }
}

if (!function_exists('getImeisReal')) {
    /**
     * 获取所有imei
     * @return array|string|null
     */
    function getImeisReal()
    {
        $imeis = request()->header('imeis');
        $imeiReal = request()->header('imei-real');
        $imeisArr = explode(',', $imeis);
        $imeisExists = false;
        foreach ($imeisArr as $item) {
            if ($item != '' && $item != '0000') {
                $imeisExists = true;
                break;
            }
        }
        if ($imeisExists) {
            $returnImei = $imeis;
        } else {
            $returnImei = $imeiReal;
        }
        logger()->info('getImeisReal', ['imei' => $returnImei]);
        return $returnImei;
    }
}

if (!function_exists('getVersion')) {
    /**
     * @return string
     */
    function getVersion()
    {
        return request()->header('app-version') ?: '';
    }
}


if (!function_exists('getHeaders')) {
    /**
     * 注意：这里的处理是针对 header中的key对应的值的在数组的第一元素中时，并且只能在控制器中使用
     * @return array
     */
    function getHeaders($header = [])
    {
        if (empty($header)) {
            $header = request()->header();
        }
        $tmp = [];
        if (is_array($header)) {
            foreach ($header as $key => $item) {
                $tmp[$key] = is_array($item) ? array_shift($item) : $item;
            }
        }
        return $tmp;
    }
}

if (!function_exists('toUnix')) {
    /**
     * @return string
     */
    function toUnix(?Carbon $carbonTime)
    {
        return $carbonTime ? $carbonTime->unix() : null;
    }
}

if (!function_exists('getAppSdk')) {
    /**
     * @return string
     */
    function getAppSdk()
    {
        return request()->header('app-sdk') ?: '';
    }
}

if (!function_exists('getIsRoot')) {
    /**
     * @return int
     */
    function getIsRoot()
    {
        return request()->header('isRoot') ? 1 : 0;
    }
}

if (!function_exists('getDeviceToken')) {
    /**
     * @return array|string|null
     */
    function getDeviceToken()
    {
        return request()->header('device-token');
    }
}

if (!function_exists('getPlatform')) {
    /**
     * @return array|string|null
     */
    function getPlatform()
    {
        return request()->header('platform', '');
    }
}

if (!function_exists('isIos')) {
    /**
     * @return bool
     */
    function isIos()
    {
        return strtolower(request()->header('platform')) == 'ios';
    }
}

if (!function_exists('centToYuan')) {
    /**
     * @param $centAmount
     * @return string
     */
    function centToYuan($centAmount)
    {
        return number_format($centAmount / 100, 2, '.', '');
    }
}

if (!function_exists('getAllHeader')) {
    /**
     * @return array
     */
    function getAllHeader()
    {
        $channel = request()->header('channel', 'miwu');
        $header = [
            'h_version' => getVersion(),
            'h_platform' => request()->header('platform'),
            'h_uuid' => getImei(),
            'h_imei_real' => getImeiReal(),
            'h_imeis' => request()->header('imeis'),
            'h_app_sdk' => getAppSdk(),
            'h_is_root' => getIsRoot(),
            'h_channel' => $channel,
        ];
        return $header;
    }
}

if (!function_exists('getClientIp')) {
    /**
     * @return array|string|null
     */
    function getClientIp()
    {
        if (request()->hasHeader('Ali-Cdn-Real-Ip')) {
            return request()->header('Ali-Cdn-Real-Ip');
        }

        return request()->getClientIp();
    }
}

if (!function_exists('hidePhoneNumber')) {
    /**
     * @param $text
     * @return string|string[]|null
     */
    function hidePhoneNumber($text)
    {
        if (is_null($text)) {
            return '';
        }

        return preg_replace('/(1[0-9]{2})([0-9]{6})([0-9]{2})/', '$1******$3', $text);
    }
}

if (!function_exists('userDefaultHeadImg')) {
    function userDefaultHeadImg()
    {
        return 'https://static1.haohuimai1.com/loseweight/portrait.png';
    }
}


if (!function_exists('hideDescription')) {
    /**
     * @param $description
     * @param int $length
     * @return string
     */
    function hideDescription($description, $length = 15)
    {
        if (strlen($description) <= $length) {
            return $description;
        }
        return mb_substr($description, 0, 15) . '...';
    }
}

if (!function_exists('decryptNoMAC')) {
    /**
     * 通过aes-128-cbc解密， IV, VALUE 预先以和客户端沟通好
     * @param $data
     * @return bool|string
     */
    function decryptNoMAC($data)
    {
        $dataArr = json_decode(base64_decode(urldecode($data)), true);
        if (json_last_error() != JSON_ERROR_NONE) {
            return false;
        }
        if (is_array($dataArr) && isset($dataArr['iv']) && isset($dataArr['value'])) {
            return openssl_decrypt(
                $dataArr['value'],
                "AES-128-CBC",
                config('app.encryption_key', ''),
                0,
                $dataArr['iv']
            );
        }
        return false;
    }
}

if (!function_exists('dataFormat')) {
    /**
     * 数据格式化，只输出struct中包含key的数据
     * @param array $struct
     * @param array $data
     * @return array
     */
    function dataFormat(array $struct, $data)
    {
        if (is_array($data)) {
            $tmpData = [];
            foreach ($data as $key => $val) {
                // FIXME: 这里需要注意， 这个val变量的类型
                if ($val instanceof Model) $val = $val->getAttributes();
                if (is_array($val)) {
                    $tmpData[$key] = dataFormat($struct, $val);
                } else if (in_array($key, $struct)) {
                    $tmpData[$key] = $val;
                }
            }
            return $tmpData;
        }
        return $data;
    }
}

if (!function_exists('getCalorie')) {
    /**
     * 卡路里计算公式
     * 体重（kg）* 距离（km）* 运动系数（k）
     * 运动系数:
     *  健走：k=0.8214
     *  跑步：k=1.036
     * @param $distance
     * @param $weight
     * @return string
     */
    function getCalorie($distance, $weight)
    {
        $calorie = $distance * $weight * 0.8214;
        return number_format($calorie, 2, '.', '');
    }
}

if (!function_exists('isLocked')) {
    /**
     * 判断是否被锁定：true 锁定中；false 未锁定
     * @param $key
     * @param $timeout
     * @return bool
     */
    function isLocked($key, $timeout = 1)
    {
        $res = redis()->set($key, 1, 'EX', $timeout, 'NX');
        return !$res;
    }
}

if (!function_exists('ExceptionLog')) {
    /**
     * 异常日志记录
     * @param $title
     * @param Request $request
     * @param Exception $e
     */
    function ExceptionLog($title, Request $request, Exception $e)
    {
        logger()->error($title,
            ['params' => $request->toArray(), 'exception' => $e]
        );
        // todo: 异常日志上报
    }
}

if (!function_exists('stringCut')) {
    /**
     * 字符串超过n个截断, '...' 填补剩余字数
     * @param $string
     * @param $n
     * @return string
     */
    function stringCut($string, $n)
    {
        $strCnt = mb_strlen($string, "utf-8");
        if ($strCnt > $n) {
            return mb_substr($string, 0, $n, "utf-8") . '...';
        }
        return $string;
    }
}

if (!function_exists('calculateBMI')) {
    /**
     * 计算 BMI 公式
     * @param string $weight 体重(单位公斤，可有小数)
     * @param string $height 身高(单位米)
     * @return float
     */
    function calculateBMI($weight, $height)
    {
        $height = (floatval($height) / 100);
        if (!$height) {
            return 0;
        }
        $res = number_format($weight / pow($height, 2), 1, '.', '');
        return $res > 100 ? 100 : $res;
    }
}
if (!function_exists('bmiValue')) {
    /**
     * 获取 bmi 对应值
     * @param $bmi
     * @param string $keyName
     * @param bool $all
     * @return array|mixed|string
     */
    function bmiValue($bmi, $keyName = 'title', $all = false)
    {
        $conf = config('global.bmi');
        foreach ($conf as $value) {
            if ($bmi >= $value['bmi_value'][0] && $bmi < $value['bmi_value'][1]) {
                if ($all) {
                    return $value;
                }
                return $value[$keyName] ?? '';
            }
        }
        return $all ? [] : '';
    }
}

if (!function_exists('perfect_weight')) {
    function perfect_weight($height, $type = 'min')
    {
        if ($type == 'min') {
            $bmi = config('global.bmi')[1]['bmi_value'][0];
        } else {
            $bmi = config('global.bmi')[1]['bmi_value'][1];
        }
        $height = (floatval($height) / 100);
        $weight = number_format($bmi * pow($height, 2), 1, '.', '');
        return $weight;
    }
}
if (!function_exists('randFloat')) {
    function randFloat($min = 0, $max = 1)
    {
        return number_format($min + mt_rand() / mt_getrandmax() * ($max - $min), 2, '.', '');
    }
}
if (!function_exists('randFloatGreater')) {
    function randFloatGreater($min = 0, $max = 10)
    {
        return number_format($min + mt_rand() / mt_getrandmax() * ($max - $min), 2, '.', '');
    }
}
if (!function_exists('staticUrl')) {
    /**
     * 阿里云静态资源地址处理
     * @param $path
     * @param bool $scale
     * @return string|null
     */
    function staticUrl($path, $rule = '')
    {
        if (is_null($path)) {
            return null;
        }

        if (Str::startsWith($path, 'http')) {
            return $path;
        }
        return config('global.static_domain') . '/' . ltrim($path, '/') . $rule;
    }
}

if (!function_exists('dealMoney')) {
    /**
     * 体重处理
     * @param $money
     * @return float
     */
    function dealMoney($money)
    {
        $money = round($money, 1);
        $moneyArr = explode('.', $money);
        if (count($moneyArr) == 2) {
            if ($moneyArr[1] == 0) {
                return $moneyArr[0];
            } else {
                return $money;
            }
        }
        return $money;
    }
}

if (!function_exists('dealWeight')) {
    /**
     * 体重处理
     * @param $weight
     * @return float
     */
    function dealWeight($weight)
    {
        $weight = round($weight, 1);
        $weightArr = explode('.', $weight);
        if (count($weightArr) == 2) {
            if ($weightArr[1] == 0) {
                return $weightArr[0];
            } else {
                return $weight;
            }
        }
        return $weight;
    }
}

if (!function_exists('subZero')) {
    /**
     * 去除小数点后的0
     * @param $value
     * @return float
     */
    function subZero($value)
    {
        $value = round($value, 1);
        $valueArr = explode('.', $value);
        if (count($valueArr) == 2) {
            if ($valueArr[1] == 0) {
                return $valueArr[0];
            } else {
                return $value;
            }
        }
        return $value;
    }
}

if (!function_exists('minutesToSeconds')) {
    /**
     * 分钟转秒
     * @param $value
     * @return float
     */
    function minutesToSeconds($minutes)
    {
        return $minutes * 60;
    }
}

if (!function_exists('treeList')) {
    /**
     * 将数值按照树结构排序
     * @param array $list 待排序数组
     * @param int $rootId 根节点id
     * @param string $pkName 根节点key
     * @param string $pidName 父节点key
     * @param string $childName 父节点name
     * @return array
     */
    function treeList($list, $rootId = 0, $pkName = 'id', $pidName = 'pid', $childName = 'children')
    {
        $data = [];
        foreach ($list as $item) {
            if ($item[$pidName] == $rootId) {
                $item[$childName] = treeList($list, $item[$pkName], $pkName, $pidName, $childName);
                $data[] = $item;
            }
        }
        return $data;
    }
}

if (!function_exists('isAudit')) {
    /**
     * 是否开启市场审核
     * @return bool
     */
    function isAudit()
    {
        $version = getVersion();
        if ('ios' == strtolower(getPlatform())) {
            $conf = config('audit.ios_prod_audit');
            if (isset($conf['version']) && in_array($version, $conf['version'])) {
                return true;
            }
            return false;
        } else {
            $conf = config('audit.android_prod_audit');
            $channel = request()->header('channel');
            if (isset($conf[$channel]) && $conf[$channel]['is_audit'] && in_array($version, $conf[$channel]['version'])) {
                return true;
            }
            return false;
        }
    }
}

/**
 * 使用时间格式化成 x分:x秒
 * @param int $seconds
 * @return string
 */
function usedTimeFormat(int $seconds, $displaySec = true)
{
    $seconds < 0 && $seconds = 0;
    $minutes = floor($seconds / 60);
    $seconds = $seconds % 60;
    if ($seconds < 10) $seconds = '0' . $seconds;
    if ($displaySec) {
        return $minutes . ":" . $seconds;
    }
    return $minutes;
}

/**
 * 数据格式化，只输出struct中包含key的数据
 * @param array $struct
 * @param array $data
 * @return array
 */
function dataFormat(array $struct, $data)
{
    if (is_array($data)) {
        $tmpData = [];
        foreach ($data as $key => $val) {
            // FIXME: 这里需要注意， 这个val变量的类型
            if ($val instanceof Model) $val = $val->getAttributes();
            if (is_array($val)) {
                $tmpData[$key] = dataFormat($struct, $val);
            } else if (in_array($key, $struct)) {
                $tmpData[$key] = $val;
            }
        }
        return $tmpData;
    }
    return $data;
}

function moneyToYuan($moneyAmount)
{
    return number_format($moneyAmount / 100, 2, '.', '');
}

function posturl($url, $data)
{
    $data = json_encode($data);
    $headerArray = array("Content-type:application/json;charset='utf-8'", "Accept:application/json");
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return json_decode($output, true);
}

if (!function_exists('getLocal')) {
    /**
     * @return array|string|null
     */
    function getLocal()
    {
        $localStr = "";
        do {
            $localInfo = request()->header('localInfo');
            if (!empty($localInfo) && strpos($localInfo, ',') > 0) {
                $localList = explode(",", $localInfo);
                $localStr = $localList[0];
                break;
            }

            $spLocal = request()->header("splocal");
            if (!empty($spLocal) && strpos($spLocal, ',') > 0) {
                $localList = explode(",", $spLocal);
                $localStr = $localList[0];
                break;
            }

            $local = request()->header('local');
            if (!empty($local)) {
                if (strpos($local, ',') > 0) {
                    //Android的local为,拼接的设备码
                    $localList = explode(",", $local);
                    $localStr = $localList[0];
                } else {
                    //iOS的local为idfa
                    $localStr = $local;
                }
                break;
            }
        } while (0);

        return $localStr;
    }
}


if (!function_exists('getHeader')) {
    function getHeader(Request $request): array
    {
        return [
            'device' => $request->header('device'),
            'version' => getVersion(),
            'localInfo' => getLocal(),
            'imei_real' => getImeiReal(),
            'platform' => $request->header('platform'),
            'imeis' => $request->header('imeis'),
            'app_sdk' => $request->header('app-sdk', ''),
            'channel' => $request->header('channel', 'sc'),
            'phone_model' => $request->header('phone-model'),
            'isRealPhone' => $request->post("IsRealPhone"),
            'hasBlueTooth' => $request->post("hasBlueTooth"),
            'hasLightSensorManager' => $request->post("hasLightSensorManager"),
            'isFeatures' => $request->post("isFeatures"),
            'readCpuInfo' => $request->post("readCpuInfo"),
            'ipAddress' => $request->post("ipAddress"),
            'wifi_ssid' => $request->post("ssid"),
            'outIpAddress' => $request->post("outIpAddress"),
            'isHasGps' => $request->post("isHasGps"),
            'netType' => $request->post("netType"),
            'aaid' => $request->header("aaid", ''),
            'vaid' => $request->header("vaid", ''),
            'android_id' => $request->header("android-id"),
            'idfa' => getIdfa(),
            'oaid' => getOaid(),
            'splocal' => $request->header('splocal'),
            // 经纬度 ios 传递
            'longitude' => $request->header("longitude"),
            'latitude' => $request->header("latitude"),
            // 客户端数据
            'location' => $request->post("location"),// 经纬度信息：纬度,经度 android 传递
            'hasPingDuoduo' => $request->post("hasPinduoduo"),
            'hasJingDong' => $request->post("hasJingDong"),
            'hasTaobao' => $request->post("hasTaobao"),
            'hasTmall' => $request->post("hasTmall"),

            'request_url' => $request->getRequestUri(),
            'ios_check' => iosCheck()
        ];
    }
}

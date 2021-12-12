<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityRequest
{
    const REQUEST_SECURITY_KEY = "s__";
    const EVERY_MINUTE_60 = 60;
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has(self::REQUEST_SECURITY_KEY)) {
            $security = $request->post(self::REQUEST_SECURITY_KEY, "");
            if (!empty($security)) {
                $data = decryptNoMAC($security);
                $dataArr = json_decode($data, true);
                logs()->info("security", ['data' => $dataArr, 'platform' => getPlatform(), 'url' => $request->url()]);
                if (is_array($dataArr) && !empty($dataArr)) {
                    foreach ($dataArr as $key => $val) {
                        $request->request->set($key, $val);
                    }
                }
            }
        }

        return $next($request);
    }
}

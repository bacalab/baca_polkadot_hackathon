<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function redisTest()
    {
        redis()->setex('test', 100, 1);
    }
}

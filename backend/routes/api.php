<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * tool router
 */
// 登录用户
Route::get("/index/{id}", "ToolController@index");
// 获取用户信息
Route::get("/user-info", "ToolController@userInfo");

/**
 * real router
 */
// ping健康检查
Route::get("/ping", "IndexController@ping");
// 中止页
// 无权限未登录
Route::get("/no-auth", "IndexController@noAuth")->name('login');

Route::get("/abort", "IndexController@abort");


// 订阅
Route::any("/subscribe", "IndexController@subscribe");
// 文件分类
Route::get("/article/category", "ArticleController@category");
// 内容列表
Route::get("/article/list", "ArticleController@items");
// 头部投票列表
Route::get("/article/topVote", "ArticleController@topVote");
// 内容详情
Route::get("/article/detail", "ArticleController@detail");
// 内容点赞
Route::get("/article/like", "ArticleController@like");
// 奖励
Route::get("/article/reward", "ArticleController@reward");
// 获取用户balance
Route::get("/user/token/balance", "UserController@tokenBalance");
// 保存用户stake信息
Route::get("/user/stake", "UserController@saveStake");
Route::get("/article/vote", "UserController@saveStake");
// 用户质押列表
Route::any("/user/stake/list", "UserController@stakeList");
// 用户信息
Route::any("/user/info", "UserController@info");


Route::middleware("security:api")->group(function () {
    // 解析 post 参数
    Route::post("/decrypt/data", "ToolController@decryptData");

    //需要登录的路由
    Route::middleware("auth:api")->group(function () {

    });
});





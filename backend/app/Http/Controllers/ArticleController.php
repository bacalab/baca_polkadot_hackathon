<?php

namespace App\Http\Controllers;

use App\Libs\Output;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    /**
     * 内容分类接口
     * @param Request $request
     * @return array
     */
    function category(Request $request)
    {
        $cate = Category::query()->select(["title", "sort", "id", "memo"])->where('status', 0)->get()->toArray();
        $data = [];
        foreach ($cate as $c) {
            $data[] = [
                "cate_img" => $c["memo"]["img"] ?? "",
                "title" => $c["title"],
                "sort" => $c["sort"],
                "id" => $c["id"],
            ];
        }

        return Output::ret(Output::SUCCEED, $data);
    }

    /**
     * 列表接口
     * @param Request $request
     * @return array
     */
    function items(Request $request)
    {
        $cate = $request->get("cid", 0);
        if ($cate) {
            $list = Article::query()->with("category")->where(["cate" => intval($cate)]);
        } else {
            $list = Article::query()->with("category");
        }
        $result = $list->orderByDesc("id")->paginate(15);

        return Output::ret(Output::SUCCEED, $result->items());
    }

    /**
     * 头部投票列表
     * @param Request $request
     * @return array
     */
    function topVote(Request $request)
    {
        $cate = $request->get("cid", 0);
        $model = Article::query()->with("category")->where(["is_top_vote" => Article::IS_TOP_VOTE]);
        if (!empty($cate)) {
            $model->where(["cate" => intval($cate)]);
        }
        $list = $model->orderByDesc("id")->paginate(15);

        return Output::ret(Output::SUCCEED, $list->items());
    }

    /**
     * 详情接口
     * @param Request $request
     * @return array
     */
    function detail(Request $request)
    {
        $aid = $request->get("aid", 0);
        if (empty($aid)) {
            goto FAIL;
        }

        $detail = Article::query()->with("category")->find(intval($aid));
        if ($detail) {
            $data = $detail->toArray();
            $authorHead = "http://www.bacamedium.com/static/images/avatar/%d.png";
            $data["author_head"] = sprintf($authorHead, $data["id"] % 10);
            return Output::ret(Output::SUCCEED, $data);
        }

        FAIL:
        return Output::ret(Output::FAILED);
    }

    /**
     * 喜欢、不喜欢接口
     * @param Request $request
     * @return array
     */
    function like(Request $request)
    {
        $user = auth()->user();
        $type = $request->get("type", 'like');
        $add = $request->get("add", 1);
        $aid = $request->get("aid", 0);
        if (empty($aid)) {
            goto FAIL;
        }
        $detail = Article::query()->find($aid);
        if ($detail) {
            if ($type == Article::TYPE_LIKE) {
                if ($add == Article::LIKE) {
                    $detail->like = $detail->like + 1;
                } else {
                    $detail->like = $detail->like - 1;
                    if ($detail->like <= 0) {
                        $detail->like = 0;
                    }
                }
            } else {
                if ($add == Article::LIKE) {
                    $detail->unlike = $detail->unlike + 1;
                } else {
                    $detail->unlike = $detail->unlike - 1;
                    if ($detail->unlike <= 0) {
                        $detail->unlike = 0;
                    }
                }
            }

            $detail->save();
            return Output::ret(Output::SUCCEED);
        }

        FAIL:
        return Output::ret(Output::FAILED);
    }


    /**
     * 时间计时器奖励
     * @param Request $request
     * @return array
     */
    function reward(Request $request)
    {
        $user = auth()->user();

        return Output::ret(Output::SUCCEED, 1);
    }
}

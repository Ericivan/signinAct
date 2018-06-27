<?php
/**
 * Created by PhpStorm.
 * User: zhongzhiliang
 * Date: 2018/6/27
 * Time: 下午10:41
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;

class WechatController extends Controller
{
    public function sign()
    {
        return request('echostr');
    }
}
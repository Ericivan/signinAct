<?php
/**
 * Created by PhpStorm.
 * User: zhongzhiliang
 * Date: 2018/4/13
 * Time: 下午10:20
 */

namespace App\Http\Controllers\Api;


use App\Events\DatabaseEvent;
use App\Http\Controllers\Controller;
use App\Video;
use Illuminate\Support\Facades\Redis;

class DatabaseAsynController extends Controller
{
    public function test()
    {
        $user = Video::find(1);

        event(new DatabaseEvent($user));

        echo 1;
    }
}
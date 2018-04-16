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
        $video = Video::find(1);

        if (isset($video)) {
            event(new DatabaseEvent($video));
            return $this->success(['sync begin']);
        }

        return $this->success(['video not found']);

    }

    public function createVideo()
    {
        Video::create([
            'name' => 'create event222',
            'length' => 100,
        ]);


        return $this->success();
    }
}
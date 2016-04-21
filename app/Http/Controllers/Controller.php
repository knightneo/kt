<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Redis;
use App\Libraries\KT\RedisKey;

class Controller extends BaseController implements RedisKey
{
    protected $request = '';

    public function __construct(Request $request)
    {
        $this->request = $request;
        $redis = Redis::connection();
    }

    public function setRedis($key, $value, $time = 60)
    {
        $value = json_encode($value);
        Redis::set($key, $value);
        Redis::expire($key, $time);
    }

    public function getRedis($key)
    {
        $data = Redis::get($key);
        return $data ? json_decode($data, true) : [];
    }
}

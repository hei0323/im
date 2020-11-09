<?php

namespace App\Providers;

use App\Http\Controllers\Test\ConfigTestController;
use App\Http\Controllers\Test\RedisTestController;
use App\Repositories\Eloquent\AliyunOss;
use App\Repositories\Eloquent\QiniuyunOss;
use App\Repositories\Eloquent\UpyunOss;
use App\Repositorys\Contracts\OssInterface;
use Illuminate\Support\ServiceProvider;

class OssServiceProvider extends ServiceProvider
{
    /**
     * 绑定接口不同的实现到容器
     *
     * @return void
     */
    public function register()
    {
        //绑定阿里云OSS服务/上下文绑定
//        $this->app->when(RedisTestController::class)
//            ->needs(OssInterface::class)
//            ->give(AliyunOss::class);
        //绑定七牛云OSS服务
        $this->app->bind(OssInterface::class,UpyunOss::class);
        //绑定又拍云OSS服务/绑定接口到实现
        //$this->app->bind(OssInterface::class,UpyunOss::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

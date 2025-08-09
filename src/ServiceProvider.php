<?php

namespace Siushin\LaravelTool;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * 注册服务
     */
    public function register()
    {
        //
    }

    /**
     * 启动服务
     */
    public function boot(): void
    {
        // 发布配置文件（可选）
        $this->publishes([
            __DIR__ . '/config/laravel-tool.php' => config_path('laravel-tool.php'),
        ], 'config');
    }
}

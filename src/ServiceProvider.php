<?php

namespace Siushin\LaravelTool;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Siushin\LaravelTool\Console\Commands\PublishConfigCommand;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * 注册服务
     * @return void
     * @author siushin<siushin@163.com>
     */
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PublishConfigCommand::class,
            ]);
        }
    }

    /**
     * 启动服务
     * @return void
     * @author siushin<siushin@163.com>
     */
    public function boot(): void
    {
        // 仅保留发布配置能力（配置已存在的话，不会自动合并）
        $this->publishes([
            __DIR__ . '/config/laravel-tool.php' => config_path('laravel-tool.php'),
        ], 'laravel-tool-config');
    }
}

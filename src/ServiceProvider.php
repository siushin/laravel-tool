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
        // 绑定工具类（可选）
        $this->app->singleton('laravel-tool', function () {
            // return new \Siushin\LaravelTool\ToolClass();
        });
    }

    /**
     * 启动服务
     */
    public function boot(): void
    {
        // 发布配置文件（可选）
        $this->publishes([
            __DIR__ . '/../config/laravel-tool.php' => config_path('laravel-tool.php'),
        ], 'config');

        // 注册命令（可选）
        if ($this->app->runningInConsole()) {
            $this->commands([
                // 添加其他命令
                // \Siushin\LaravelTool\Console\Commands\YourCommand::class,
            ]);
        }
    }
}
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
        $configPath = $this->getConfigPath();
        $this->publishes([
            __DIR__ . '/config/laravel-tool.php' => $configPath,
        ], 'laravel-tool-config');
    }

    /**
     * 获取配置文件发布路径
     * @return string
     */
    protected function getConfigPath(): string
    {
        // 检查是否安装了 nwidart/laravel-modules
        if (class_exists('Nwidart\Modules\LaravelModulesServiceProvider')) {
            $modulesConfigPath = base_path('Modules/Base/config');
            // 检查 Modules/Base/config 目录是否存在
            if (is_dir($modulesConfigPath)) {
                return $modulesConfigPath . '/laravel-tool.php';
            }
        }

        // 默认路径
        return config_path('laravel-tool.php');
    }
}

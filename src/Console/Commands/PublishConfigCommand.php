<?php

namespace Siushin\LaravelTool\Console\Commands;

use Illuminate\Console\Command;

/**
 * Command命令行：发布配置
 */
class PublishConfigCommand extends Command
{
    protected $signature   = 'laravel-tool:publish';
    protected $description = 'Publish Laravel Tool config';

    public function handle(): void
    {
        $this->call('vendor:publish', [
            '--provider' => 'Siushin\LaravelTool\ServiceProvider',
            '--tag' => 'laravel-tool-config'
        ]);
    }
}

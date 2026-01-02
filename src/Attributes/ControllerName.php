<?php

namespace Siushin\LaravelTool\Attributes;

use Attribute;

/**
 * 控制器名称属性
 * 用于在控制器类上标记控制器名称，便于IDE解析和代码管理
 *
 * 使用示例：
 * #[ControllerName('系统日志')]
 * class LogController extends Controller
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ControllerName
{
    public function __construct(
        public string $name
    )
    {
    }
}


<?php
declare(strict_types=1);

namespace Siushin\LaravelTool\Enums;

/**
 * 枚举：性别
 */
enum SysGenderType: string
{
    case male    = '男';
    case female  = '女';
    case unknown = '未知';
}

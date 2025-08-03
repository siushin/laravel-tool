<?php
declare(strict_types=1);

namespace Siushin\LaravelTool\Enums;

enum SysUserType: string
{
    case admin = '管理员';     // 管理后台登录用户
    case user  = '用户';       // 用户端登录用户
    case we    = '小程序用户';  // 小程序端登录用户
    case guest = '匿名访客';    // 没有登录的访客
}

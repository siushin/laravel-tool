<?php

namespace Siushin\LaravelTool\Enums;

/**
 * 枚举：用户类型
 */
enum SysUserType: string
{
    case admin = '管理员';     // 管理员用户
    case user  = '用户';       // 用户端登录用户
    case app   = 'APP用户';    // APP用户
    case we    = '小程序用户';  // 小程序用户
    case guest = '匿名访客';    // 匿名用户
}

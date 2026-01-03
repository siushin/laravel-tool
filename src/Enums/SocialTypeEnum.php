<?php

namespace Siushin\LaravelTool\Enums;

/**
 * 枚举：社交类型
 */
enum SocialTypeEnum: string
{
    case Mobile  = 'mobile';    // 手机号
    case Email   = 'email';     // 邮箱
    case Wechat  = 'wechat';    // 微信
    case Weibo   = 'weibo';     // 微博
    case AppleId = 'apple_id';  // 苹果ID
    case Github  = 'github';    // GitHub
}

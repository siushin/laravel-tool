<?php

namespace Siushin\LaravelTool\Enums;

/**
 * 枚举：请求来源
 */
enum RequestSourceEnum: string
{
    // Web端
    case web        = 'Web端';             // PC浏览器
    case mobile_web = '移动Web';           // 移动浏览器

    // APP端
    case ios     = 'iOS应用';              // iOS原生应用
    case android = 'Android应用';          // Android原生应用
    case app     = 'APP应用';              // 通用APP应用

    // 小程序
    case wechat_mini = '微信小程序';       // 微信小程序
    case alipay_mini = '支付宝小程序';     // 支付宝小程序
    case douyin_mini = '抖音小程序';       // 抖音小程序
    case baidu_mini  = '百度小程序';       // 百度小程序
    case qq_mini     = 'QQ小程序';         // QQ小程序
    case mini        = '小程序';           // 通用小程序

    // 管理端
    case admin     = '管理后台';            // 管理后台
    case admin_api = '管理后台API';         // 管理后台API

    // API调用
    case api         = 'API接口';           // 通用API接口
    case third_party = '第三方系统';         // 第三方系统调用
    case open_api    = '开放API';           // 开放平台API

    // 系统内部
    case cron     = '定时任务';              // 定时任务/计划任务
    case console  = '命令行';                // 命令行/CLI
    case queue    = '队列任务';              // 队列任务
    case internal = '内部调用';              // 内部系统调用

    // 其他
    case guest = '游客';                     // 游客
}

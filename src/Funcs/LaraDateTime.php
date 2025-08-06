<?php

/**
 * 助手函数：日期时间（基于Laravel）
 */

use Illuminate\Support\Carbon;

/**
 * 获取本地化日期时间
 * @param $datetime
 * @return string
 * @author siushin<siushin@163.com>
 */
function getLocaleDateTime($datetime): string
{
    return Carbon::parse($datetime)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
}

/**
 * 获取日期时间（根据时间戳）
 * @param $timestamp
 * @return string
 * @author siushin<siushin@163.com>
 */
function getDateTimeByTimestamp($timestamp): string
{
    $timezone = config('app.timezone');
    return Carbon::createFromTimestamp($timestamp, $timezone)->toDateTimeString();
}

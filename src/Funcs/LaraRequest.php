<?php
declare(strict_types=1);

/**
 * 助手函数：Request请求（基于Laravel）
 */

/**
 * 获取当前登录用户ID
 * @param int $defaultUserId
 * @return int
 */
function currentUserId(int $defaultUserId = 0): int
{
    $user = request()->user() ?? [];
    return $user['id'] ??= $defaultUserId;
}

/**
 * 构造文件路径（去掉多余的/）
 * @param string $dir_path     目录名
 * @param string $file_name    文件名（支持带目录路径）
 * @param bool   $is_full_path 是否响应完整路径，默认响应相对路径
 * @return string
 */
function buildFilePath(string $dir_path, string $file_name, bool $is_full_path = true): string
{
    // 重新构建URL（不包含查询字符串）
    $dir_path = explode(DIRECTORY_SEPARATOR, trim($dir_path, DIRECTORY_SEPARATOR));
    $file_name = explode(DIRECTORY_SEPARATOR, trim($file_name, DIRECTORY_SEPARATOR));
    // 合并path重组，并去掉尾部斜杆/
    $file_path = rtrim(DIRECTORY_SEPARATOR . ltrim(implode(DIRECTORY_SEPARATOR, array_merge($dir_path, $file_name)), DIRECTORY_SEPARATOR), DIRECTORY_SEPARATOR);
    return $is_full_path ? base_path() . '/' . ltrim($file_path, DIRECTORY_SEPARATOR) : $file_path;
}

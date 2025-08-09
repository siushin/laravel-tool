<?php

namespace Siushin\LaravelTool\Enums;

/**
 * 枚举：操作类型（日志）
 */
enum SysLogAction: string
{
    case login        = '登录';
    case fail_login   = '登录失败';
    case insert       = '新增数据';
    case update       = '更新数据';
    case delete       = '删除数据';
    case batchDelete  = '批量删除数据';
    case export_excel = '导出Excel';
    case export_pdf   = '导出PDF';
    case export_csv   = '导出CSV';
    case export_txt   = '导出TXT';
    case export_zip   = '导出ZIP';
    case upload_file  = '上传文件';
}

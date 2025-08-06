<?php

namespace Siushin\LaravelTool\Enums;

/**
 * 枚举：上传文件类型
 */
enum SysUploadFileType: string
{
    // 图片
    case JPG  = 'jpg';
    case JPEG = 'jpeg';
    case PNG  = 'png';
    case GIF  = 'gif';

    // PDF文件
    case PDF = 'pdf';

    // 文档
    case DOC  = 'doc';
    case DOCX = 'docx';
    case XLS  = 'xls';
    case XLSX = 'xlsx';
    case CSV  = 'csv';

    // 文本
    case TXT = 'txt';
}

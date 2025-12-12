<?php

/**
 * 助手函数：Response响应（基于Laravel）
 */

use Illuminate\Http\JsonResponse;

/**
 * HTTP 响应成功
 * @param array  $data
 * @param string $message
 * @param int    $code
 * @return JsonResponse
 * @author siushin<siushin@163.com>
 */
function success(array $data = [], string $message = 'success', int $code = 200): JsonResponse
{
    $data = compact('code', 'message', 'data');
    return response()->json($data);
}

/**
 * 抛出HTTP异常
 * @param string $message
 * @param int    $error_code
 * @param int    $code
 * @return Exception
 * @throws Exception
 * @author siushin<siushin@163.com>
 */
function throw_exception(string $message = 'fail', int $error_code = 0, int $code = 0): Exception
{
    $is_json_data = json_decode($message, true);
    if (is_array($is_json_data) && isset($is_json_data['message'])) {
        $message = $is_json_data['message'];
    }
    if ($error_code !== 0) {
        $message .= "，错误码：$error_code";
    }
    $status_code = $code ?: 401;
    $data = json_encode(compact('code', 'message'), JSON_UNESCAPED_UNICODE);
    throw new Exception($data, $status_code);
}

/**
 * 构造分页数据
 * @param mixed $data
 * @return array
 * @author siushin<siushin@163.com>
 */
function buildPageData(mixed $data): array
{
    gettype($data) == 'object' && $data = $data->toArray();
    return [
        'data' => $data['data'] ?? [],
        'page' => [
            'currentPage'  => $data['current_page'] ?? 1,    // 当前页数
            'currentCount' => $data['to'] ?: 0,             // 当前记录数
            'perPage'      => (int)($data['per_page'] ?? 10),    // 每页记录数
            'lastPage'     => $data['last_page'] ?? 1,          // 总页数（最后页数）
            'total'        => $data['total'] ?? 0,                 // 总记录数
        ],
    ];
}

<?php
declare(strict_types=1);

namespace Siushin\LaravelTool\Traits;

use Illuminate\Database\Eloquent\Collection;
use Siushin\Util\Traits\ParamTool;

/**
 * 工具类：参数（基于Laravel）
 */
trait LaraParamTool
{
    use ParamTool;

    /**
     * 集合数据递归转数组
     * @param mixed $collection
     * @return array
     * @author siushin<siushin@163.com>
     */
    public static function collectRecursiveToArray(mixed $collection): array
    {
        if ($collection instanceof Collection) {
            return $collection->map(function ($item) {
                return self::collectRecursiveToArray($item);
            })->toArray();
        } elseif (is_object($collection)) {
            return get_object_vars($collection);
        } else {
            return $collection;
        }
    }
}
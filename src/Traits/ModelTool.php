<?php

namespace Siushin\LaravelTool\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * 工具类：模型常用方法
 */
trait ModelTool
{
    use LaraParamTool;

    /**
     * 访问器（将日期时间字段转换为本地时区）
     * @param $value
     * @return string
     * @author siushin<siushin@163.com>
     */
    public function getCreatedAtAttribute($value): string
    {
        return Carbon::parse($value)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
    }

    /**
     * 访问器（将日期时间字段转换为本地时区）
     * @param $value
     * @return string
     * @author siushin<siushin@163.com>
     */
    public function getUpdatedAtAttribute($value): string
    {
        return Carbon::parse($value)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
    }

    /**
     * 检查是否存在数据（根据主键ID）
     * @param array  $params
     * @param string $pk
     * @return void
     * @throws Exception
     * @author siushin<siushin@163.com>
     */
    public static function checkIsExistByPk(array $params, string $pk): void
    {
        self::checkEmptyParam($params, [$pk]);
        $info = (new self)->query()->find($params[$pk]);
        !$info && throw_exception("数据不存在(ID:$params[$pk])");
    }

    /**
     * 获取表的所有字段清单
     * @return array
     * @author siushin<siushin@163.com>
     */
    public static function getTableFields(): array
    {
        return Schema::getColumnListing((new self)->getTable());
    }

    /**
     * 构造分页数据的查询参数
     * @param array $params
     * @return array
     * @throws Exception
     * @author siushin<siushin@163.com>
     */
    public static function buildQueryReqOfPageData(array $params = []): array
    {
        $page = $params['page'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;
        // 排序，默认created_at
        if (isset($params['without_sort'])) {
            $sortbys = [];
        } else {
            $sortbys = ['created_at' => 'desc'];
            if (!empty($params['sortbys'])) {
                $sortbys = self::getQueryParam($params, 'sortbys', [], '@');
                $sortbys = collect($sortbys)->filter(fn($value) => in_array($value, ['desc', 'asc']))->toArray();
            }
        }
        $keyword = $params['keyword'] ?? null;
        $date_range = $params['date_range'] ?? '';
        $date_range && $date_range = explode(',', $date_range);
        $time_range = $params['time_range'] ?? '';
        $time_range && $time_range = explode(',', $time_range);
        return compact('page', 'pageSize', 'sortbys', 'keyword', 'date_range', 'time_range');
    }

    /**
     * 构造where数组
     * @param array $params
     * @param array $where_mapping
     * @param array $where
     * @return array
     * @throws Exception
     * @author siushin<siushin@163.com>
     */
    public static function buildWhereData(array $params, array $where_mapping, array $where = []): array
    {
        foreach ($where_mapping as $field => $val) {
            $field_data = $params[$field] ?? '';
            if ($field_data === '') continue;
            // 特殊处理：keyword、time_range
            if ($field == 'keyword') {
                // 只处理 单个字段 筛选（单一关键字 多字段 like 筛选 留给后面复杂模型组合筛选）
                $keyword_field = self::getQueryParam($where_mapping, 'keyword', '');
                Str::substrCount($keyword_field, '|') == 0 && $where[] = [$val, 'like', "%$field_data%"];
            } else if ($field == 'date_range') {
                list('date_range' => $date_range) = self::buildQueryReqOfPageData($params);
                $where[] = [$val, '>=', $date_range[0]];
                $where[] = [$val, '<=', $date_range[1]];
            } else if ($field == 'time_range') {
                list('time_range' => $time_range) = self::buildQueryReqOfPageData($params);
                $where[] = [$val, '>=', $time_range[0]];
                $where[] = [$val, '<=', $time_range[1]];
            } else {
                $where[] = match ($val) {
                    'like', 'not like' => [$field, $val, "%$field_data%"],
                    default => [$field, $val, $field_data],
                };
            }
        }
        return $where;
    }

    /**
     * 公共快速请求数据的参数处理
     * @param mixed        $model
     * @param array        $params
     * @param array        $where_mapping
     * @param array|string $fields
     * @param array        $where
     * @return mixed
     * @throws Exception
     * @author siushin<siushin@163.com>
     */
    public static function baseFastParamHandle(mixed $model, array $params, array $where_mapping = [], array|string $fields = '*', array $where = []): mixed
    {
        list(
            'sortbys' => $sortbys,
            ) = self::buildQueryReqOfPageData($params);
        $where = array_merge(self::buildWhereData($params, $where_mapping), $where);
        if (is_string($model) && class_exists($model)) {
            $model = $model::where($where);
        } else {
            $model = $model ? $model->where($where) : (new self)->query();
        }

        // 单一关键字 多字段 like 筛选
        $keyword_field = self::getQueryParam($where_mapping, 'keyword', '');
        if (Str::substrCount($keyword_field, '|') > 0) {
            $keyword = self::getQueryParam($params, 'keyword');
            $where_likes = explode('|', $where_mapping['keyword']);
            $model = $model->where(function (Builder $query) use ($where_likes, $keyword) {
                foreach ($where_likes as $index => $like_field) {
                    $query->when($index == 0, function (Builder $query) use ($like_field, $keyword) {
                        $query->where($like_field, 'like', "%$keyword%");
                    }, function (Builder $query) use ($like_field, $keyword) {
                        $query->orWhere($like_field, 'like', "%$keyword%");
                    });
                }
            });
        }

        if ($sortbys && is_array($sortbys)) {
            foreach ($sortbys as $field => $sort) {
                $model = $model->orderBy($field, $sort);
            }
        }

        // 将$fields参数统一转换成一维数组，支持字符串逗号分隔和数组格式
        $fields = is_string($fields)
            ? array_filter(
                preg_split('/[\s,]+/', trim($fields), -1, PREG_SPLIT_NO_EMPTY),
                fn($field) => $field !== ''
            )
            : $fields;
        $fields = array_unique(array_map('trim', $fields)); // 去除每个字段两边的空格、去除重复字段

        return $model->select(...$fields);
    }

    /**
     * 快速获取分页数据
     * @param mixed        $model
     * @param array        $params
     * @param array        $where_mapping
     * @param array|string $fields
     * @param array        $where
     * @return array
     * @throws Exception
     * @author siushin<siushin@163.com>
     */
    protected static function fastGetPageData(mixed $model, array $params, array $where_mapping = [], array|string $fields = ['*'], array $where = []): array
    {
        list(
            'page' => $page,
            'pageSize' => $pageSize,
            ) = self::buildQueryReqOfPageData($params);
        $model = self::baseFastParamHandle($model, $params, $where_mapping, $fields, $where);
        // dd($model->toRawSql());
        $data = $model->paginate($pageSize, $fields, 'page', $page);
        return buildPageData($data);
    }

    /**
     * 快速获取全部数据
     * @param mixed        $model
     * @param array        $params
     * @param array        $where_mapping
     * @param array|string $fields
     * @param array        $where
     * @return array
     * @throws Exception
     * @author siushin<siushin@163.com>
     */
    protected static function fastGetAllData(mixed $model, array $params, array $where_mapping = [], array|string $fields = ['*'], array $where = []): array
    {
        $model = self::baseFastParamHandle($model, $params, $where_mapping, $fields, $where);
        // dd($model->toRawSql());
        return $model->get()->toArray();
    }

    /**
     * 快速获取条目数
     * @param mixed        $model
     * @param array        $params
     * @param array        $where_mapping
     * @param array|string $fields
     * @param array        $where
     * @return int
     * @throws Exception
     * @author siushin<siushin@163.com>
     */
    public static function fastGetTotal(mixed $model, array $params, array $where_mapping = [], array|string $fields = ['*'], array $where = []): int
    {
        $model = self::baseFastParamHandle($model, $params, $where_mapping, $fields, $where);
        return $model->get()->count();
    }

    /**
     * 构造完整行政区划数据
     * @param array $region_list
     * @return int[]|null[]
     * @author siushin<siushin@163.com>
     */
    public static function buildFullRegionData(array $region_list): array
    {
        $province = (int)current($region_list) ?: null;
        $city = (int)next($region_list) ?: null;
        $area = (int)next($region_list) ?: null;
        $town = (int)next($region_list) ?: null;
        return [$province, $city, $area, $town];
    }

    /**
     * 构造行政区划 where筛选条件
     * @param array $region_list
     * @param array $where
     * @return array
     * @author siushin<siushin@163.com>
     */
    public static function buildRegionWhereData(array $region_list, array $where = []): array
    {
        [$province, $city, $area, $town] = self::buildFullRegionData($region_list);
        isset($province) && $where[] = ['province', '=', $province];
        isset($city) && $where[] = ['city', '=', $city];
        isset($area) && $where[] = ['area', '=', $area];
        isset($town) && $where[] = ['town', '=', $town];
        return $where;
    }

    /**
     * 为行政区划数据设默认值（不存在也会追加）
     * @param array $params
     * @return void
     * @author siushin<siushin@163.com>
     */
    public static function handleDefaultValueRegionData(array &$params): void
    {
        $params['province'] = (int)$params['province'] ?? 0;
        $params['city'] = (int)$params['city'] ?? 0;
        $params['area'] = (int)$params['area'] ?? 0;
        $params['town'] = (int)$params['town'] ?? 0;
    }

    /**
     * 生成 数据不存在 消息
     * @param int    $id
     * @param string $field
     * @return string
     * @author siushin<siushin@163.com>
     */
    protected static function notFoundDataMsg(int $id, string $field = 'ID'): string
    {
        return "数据不存在($field:$id)";
    }
}
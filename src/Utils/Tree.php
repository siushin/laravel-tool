<?php
declare(strict_types=1);

namespace Siushin\LaravelTool\Utils;

/**
 * 工具类：树形结构
 */
class Tree
{
    protected string $id;       // ID
    protected string $pid;      // 父ID
    protected string $children; // children
    protected string $level;    // 层级

    public function __construct(string $id = 'id', string $pid = 'pid', string $children = 'children', string $level = 'level')
    {
        $this->id = $id;
        $this->pid = $pid;
        $this->children = $children;
        $this->level = $level;
    }

    /**
     * 生成tree数组
     * @param array $array
     * @param int   $pid
     * @param int   $level
     * @return array
     */
    public function getTree(array $array, int $pid = 0, int $level = 0): array
    {
        // 转换对象为数组并构建 childrenMap
        $childrenMap = [];
        foreach ($array as $item) {
            $item = (array)$item;
            $parentId = $item[$this->pid];
            $childrenMap[$parentId][] = $item;
        }

        return $this->buildTree($childrenMap, $pid, $level);
    }

    /**
     * 递归构建树
     * @param array $childrenMap 父节点ID到子节点列表的映射
     * @param int   $parentId    当前父节点ID
     * @param int   $level       当前层级
     * @return array
     */
    private function buildTree(array $childrenMap, int $parentId, int $level): array
    {
        $tree = [];
        if (!isset($childrenMap[$parentId])) {
            return $tree;
        }

        foreach ($childrenMap[$parentId] as $item) {
            $item[$this->level] = $level;
            $childTree = $this->buildTree($childrenMap, $item[$this->id], $level + 1);
            $this->setChildrenAndLeaf($item, $childTree);
            $tree[] = $item;
        }

        return $tree;
    }

    /**
     * 设置子节点和 leaf 属性
     * @param array $item      当前节点引用
     * @param array $childTree 子节点树
     */
    private function setChildrenAndLeaf(array &$item, array $childTree): void
    {
        if (!empty($childTree)) {
            $item['leaf'] = false;
            $item[$this->children] = $childTree;
        } else {
            $item['leaf'] = true;
            $item[$this->children] = [];
        }
    }
}
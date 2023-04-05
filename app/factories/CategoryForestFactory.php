<?php

namespace App\Factories;
use Phalcon\Mvc\Model\Manager;

class CategoryForestFactory
{
    protected $modelsManager;

    public function __construct(Manager $modelsManager)
    {
        $this->modelsManager = $modelsManager;
    }

    public function create($categories) {
        return $this->buildForest($categories);
    }

    private function buildForest($nodes)
    {
        $parentIds = $this->getParentIds($nodes);

        if (empty($parentIds)) {
            return $nodes;
        }

        $roots = array_filter($nodes, function ($category) {
            return $category['parentCategoryId'] == null;
        });

        $children = array_filter($nodes, function ($category) {
            return $category['parentCategoryId'] != null;
        });

        do {
            $parents = $this->getParents($parentIds);

            foreach ($parents as &$parent) {
                $parent['children'] = $this->getChildren($children, $parent['id']);
            }

            $this->removeDuplicates($parents, $roots);

            $roots = array_merge($roots, array_filter($parents, function ($node) {
                return $node['parentCategoryId'] == null;
            }));

            $children = array_filter($parents, function ($category) {
                return $category['parentCategoryId'] != null;
            });

            $parentIds = $this->getParentIds($children);
        } while (! empty($parentIds));

        return $roots;
    }

    private function getParents($parentIds)
    {
        return $this->modelsManager
            ->createBuilder()
            ->columns([
                "Category.id",
                "Category.name",
                "Category.parentCategoryId"
            ])
            ->from(["Category" => "App\Models\Category"])
            ->inWhere("Category.id", $parentIds)
            ->groupBy("Category.id")
            ->getQuery()
            ->execute()
            ->toArray(); 
    }

    private function getParentIds($categories)
    {
        $parentIds = array_map(function ($category) {
            return $category['parentCategoryId'];
        }, $categories);

        $parentIds = array_filter($parentIds,function ($parentId) {
            return $parentId != null;
        });

        $parentIds = array_unique($parentIds);

        return $parentIds;
    }

    private function getChildren($matches, $parentId)
    {
        return array_values(array_filter($matches, function($category) use ($parentId) {
            return $category['parentCategoryId'] == $parentId;
        }));
    }

    private function removeDuplicates(&$parents, &$roots) {
        $clonesInRoots = array_filter($parents, function ($parent) use ($roots) {
            return count(array_filter($roots, function ($root) use ($parent) {
                return $root['id'] == $parent['id'];
            })) > 0;
        });

        foreach ($parents as $parentKey => $parent) {
            foreach ($roots as $rootKey => $root) {
                if ($parent['id'] == $root['id']) {
                    $rootChildren = isset($roots[$rootKey]['children']) ? $roots[$rootKey]['children'] : [];
                    $roots[$rootKey]['children'] = array_merge($parent['children'], $rootChildren);

                    unset($parents[$parentKey]);
                }
            }
        }
    }

}
<?php

namespace Botble\Base\Supports;

use Botble\Menu\Models\MenuNode;
use Illuminate\Support\Collection;

class MenuPreloader
{
    protected static array $preloadedMenus = [];

    public static function preloadMenu($menuId): void
    {
        if (isset(static::$preloadedMenus[$menuId])) {
            return;
        }

        $menuNodes = MenuNode::query()
            ->where('menu_id', $menuId)
            ->with(['reference', 'metadata'])
            ->get();

        if ($menuNodes->isNotEmpty()) {
            MetadataCache::preloadForModels(
                $menuNodes->all(),
                ['icon_image', 'hide_on_mobile', 'target', 'css_class']
            );
        }

        $nodesByParent = $menuNodes->groupBy('parent_id');

        foreach ($menuNodes as $node) {
            $node->setRelation('child', $nodesByParent->get($node->id, collect()));
        }

        static::$preloadedMenus[$menuId] = [
            'nodes' => $menuNodes,
            'by_parent' => $nodesByParent,
        ];
    }

    public static function getPreloadedNodes($menuId, $parentId = 0): Collection
    {
        if (! isset(static::$preloadedMenus[$menuId])) {
            return collect();
        }

        return static::$preloadedMenus[$menuId]['by_parent']->get($parentId, collect());
    }

    public static function flush(): void
    {
        static::$preloadedMenus = [];
        MetadataCache::flush();
    }
}

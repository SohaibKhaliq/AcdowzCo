<?php

namespace Botble\Base\Supports;

use Botble\Base\Models\MetaBox;

class MetadataCache
{
    protected static array $cache = [];

    protected static bool $cacheEnabled = true;

    public static function enable(): void
    {
        static::$cacheEnabled = true;
    }

    public static function disable(): void
    {
        static::$cacheEnabled = false;
    }

    public static function get(string $key, $model): mixed
    {
        if (! static::$cacheEnabled) {
            return null;
        }

        $cacheKey = static::getCacheKey($model, $key);

        if (isset(static::$cache[$cacheKey])) {
            return static::$cache[$cacheKey];
        }

        return null;
    }

    public static function set(string $key, $model, $value): void
    {
        if (! static::$cacheEnabled) {
            return;
        }

        $cacheKey = static::getCacheKey($model, $key);
        static::$cache[$cacheKey] = $value;
    }

    public static function forget(string $key, $model): void
    {
        $cacheKey = static::getCacheKey($model, $key);
        unset(static::$cache[$cacheKey]);
    }

    public static function flush(): void
    {
        static::$cache = [];
    }

    public static function preloadForModels(array $models, array $keys): void
    {
        if (empty($models) || empty($keys) || ! static::$cacheEnabled) {
            return;
        }

        $modelIds = collect($models)->pluck('id')->filter()->all();

        if (empty($modelIds)) {
            return;
        }

        $modelClass = get_class($models[0]);
        $metadata = MetaBox::query()
            ->whereIn('reference_id', $modelIds)
            ->where('reference_type', $modelClass)
            ->whereIn('meta_key', $keys)
            ->get()
            ->groupBy('reference_id');

        foreach ($models as $model) {
            $modelMeta = $metadata->get($model->id, collect());

            foreach ($keys as $key) {
                $meta = $modelMeta->firstWhere('meta_key', $key);
                $value = $meta ? $meta->meta_value : null;
                static::set($key, $model, $value);
            }
        }
    }

    protected static function getCacheKey($model, string $key): string
    {
        $id = method_exists($model, 'getKey') ? $model->getKey() : $model->id;

        return sprintf(
            '%s:%s:%s',
            get_class($model),
            $id,
            $key
        );
    }
}

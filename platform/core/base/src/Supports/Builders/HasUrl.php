<?php

namespace Botble\Base\Supports\Builders;

use Botble\Table\Actions\Action;
use Closure;

trait HasUrl
{
    protected Closure|string $url;

    protected bool $openUrlInNewTab = false;

    /**
     * @param \Closure(static): string|string $url
     */
    public function url(Closure|string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function hasUrl(): bool
    {
        return isset($this->url);
    }

    public function getUrl(): ?string
    {
        if (! $this->hasUrl()) {
            return null;
        }

        return $this->url instanceof Closure ? call_user_func($this->url, $this) : $this->url;
    }

    public function openUrlInNewTable(bool $openUrlInNewTab = true): static
    {
        $this->openUrlInNewTab = $openUrlInNewTab;

        return $this;
    }

    public function shouldOpenUrlInNewTable(): bool
    {
        return $this->openUrlInNewTab;
    }

    public function route(string $route, array $parameters = [], bool $absolute = true): static
    {
        $this
            ->url(fn(Action $action) => $this->generateRouteUrl($route, $parameters, $absolute, $action))
            ->permission($route);

        return $this;
    }

    protected function generateRouteUrl(string $route, array $parameters, bool $absolute, Action $action): string
    {
        // Avoid throwing if the named route isn't registered — return '#' instead.
        if (\Route::has($route)) {
            return route($route, array_merge($parameters, [$action->getItem()->getKey()]), $absolute);
        }

        // Check for prefixed variation
        if (\Route::has("ecommerce.{$route}")) {
            return route("ecommerce.{$route}", array_merge($parameters, [$action->getItem()->getKey()]), $absolute);
        }

        return '#';
    }
}

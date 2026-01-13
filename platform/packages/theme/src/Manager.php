<?php

namespace Botble\Theme;

use Botble\Base\Facades\BaseHelper;
use Botble\Theme\Facades\Theme as ThemeFacade;
use Botble\Theme\Services\ThemeService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class Manager
{
    protected array $themes = [];

    protected string $cacheKey = 'theme_manager_all_themes';

    protected int $cacheTtl = 3600 * 5;

    public function __construct(protected ThemeService $themeService)
    {
        $this->registerTheme($this->getAllThemes());
    }

    public function registerTheme(string|array $theme): void
    {
        if (! is_array($theme)) {
            $theme = [$theme];
        }

        $this->themes = array_merge_recursive($this->themes, $theme);
    }

    public function getAllThemes(): array
    {
        if ($this->shouldInvalidateCache()) {
            $this->clearCache();
        }

        return Cache::remember($this->cacheKey, $this->cacheTtl, function () {
            $themes = $this->loadThemesFromFileSystem();

            Cache::put($this->cacheKey . '_timestamp', time(), $this->cacheTtl);

            return $themes;
        });
    }

    protected function loadThemesFromFileSystem(): array
    {
        $themes = [];
        foreach (BaseHelper::scanFolder(theme_path()) as $folder) {
            $jsonFile = $this->getThemeJsonPath($folder);

            $publicJsonFile = public_path('themes/' . ThemeFacade::getPublicThemeName() . '/theme.json');

            if (File::exists($publicJsonFile)) {
                $jsonFile = $publicJsonFile;
            }

            if (! File::exists($jsonFile)) {
                continue;
            }

            $theme = BaseHelper::getFileData($jsonFile);

            if (! empty($theme)) {
                $themeConfig = $this->themeService->getThemeConfig($folder);

                $themes[$folder] = $theme;
                $themes[$folder]['inherit'] = Arr::get($themeConfig, 'inherit');
            }
        }

        return $themes;
    }

    public function getThemePresets(string $theme): array
    {
        $cacheKey = "theme_presets_{$theme}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($theme) {
            if (! $theme || ! File::exists($jsonFile = $this->getThemeJsonPath($theme))) {
                return [];
            }

            $themeData = BaseHelper::getFileData($jsonFile);

            return $themeData['presets'] ?? [];
        });
    }

    public function getThemes(): array
    {
        return $this->themes;
    }

    public function clearCache(): void
    {
        Cache::forget($this->cacheKey);
        Cache::forget($this->cacheKey . '_timestamp');

        foreach (BaseHelper::scanFolder(theme_path()) as $folder) {
            Cache::forget("theme_presets_{$folder}");
        }
    }

    public function refreshThemes(): array
    {
        $this->clearCache();
        $this->themes = [];
        $this->registerTheme($this->getAllThemes());

        return $this->themes;
    }

    protected function shouldInvalidateCache(): bool
    {
        $cacheTimestamp = Cache::get($this->cacheKey . '_timestamp');

        if (! $cacheTimestamp) {
            return true;
        }

        $themePath = theme_path();

        if (! File::isDirectory($themePath)) {
            return false;
        }

        foreach (BaseHelper::scanFolder($themePath) as $folder) {
            $jsonFile = $this->getThemeJsonPath($folder);

            if (File::exists($jsonFile) && File::lastModified($jsonFile) > $cacheTimestamp) {
                return true;
            }
        }

        return false;
    }

    protected function getThemeJsonPath(string $theme): string
    {
        return  theme_path() . '/' . $theme . '/theme.json';
    }
}

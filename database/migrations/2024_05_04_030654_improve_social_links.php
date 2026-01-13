<?php

use Botble\Setting\Facades\Setting;
use Botble\Theme\Facades\ThemeOption;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Arr;

return new class() extends Migration
{
    public function up(): void
    {
        $oldSocialLinks = theme_option('social_links');
        if (! $oldSocialLinks) {
            return;
        }

        $oldSocialLinks = json_decode($oldSocialLinks, true);

        $socialLinks = [];
        foreach ($oldSocialLinks as $socialLink) {
            if (count($socialLink) == 3) {
                $socialLinks[] = [
                    ['key' => 'name', 'value' => Arr::get($socialLink[0], 'value')],
                    ['key' => 'icon', 'value' => null],
                    ['key' => 'url', 'value' => Arr::get($socialLink[2], 'value')],
                    ['key' => 'image', 'value' => Arr::get($socialLink[1], 'value')],
                    ['key' => 'color', 'value' => '#fff'],
                    [
                        'key' => 'background-color',
                        'value' => theme_option('primary_button_background_color') ?: theme_option(
                            'primary_color',
                            '#fab528'
                        ),
                    ],
                ];
            }
        }

        Setting::delete('social_links');

        $socialSharingButtons = [
            [
                ['key' => 'social', 'value' => 'facebook'],
                ['key' => 'icon', 'value' => 'ti ti-brand-facebook'],
                ['key' => 'icon_image', 'value' => null],
                ['key' => 'color', 'value' => '#fff'],
                ['key' => 'background_color', 'value' => '#3b5999'],
            ],
            [
                ['key' => 'social', 'value' => 'x'],
                ['key' => 'icon', 'value' => 'ti ti-brand-twitter'],
                ['key' => 'icon_image', 'value' => null],
                ['key' => 'color', 'value' => '#fff'],
                ['key' => 'background_color', 'value' => '#55acee'],
            ],
            [
                ['key' => 'social', 'value' => 'pinterest'],
                ['key' => 'icon', 'value' => 'ti ti-brand-pinterest'],
                ['key' => 'icon_image', 'value' => null],
                ['key' => 'color', 'value' => '#fff'],
                ['key' => 'background_color', 'value' => '#b10c0c'],
            ],
            [
                ['key' => 'social', 'value' => 'linkedin'],
                ['key' => 'icon', 'value' => 'ti ti-brand-linkedin'],
                ['key' => 'icon_image', 'value' => null],
                ['key' => 'color', 'value' => '#fff'],
                ['key' => 'background_color', 'value' => '#0271ae'],
            ],
        ];

        Setting::set(ThemeOption::prepareFromArray([
            'social_links' => $socialLinks,
            'social_sharing' => $socialSharingButtons,
        ]));

        Setting::save();
    }
};

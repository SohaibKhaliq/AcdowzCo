<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Botble\Language\Facades\Language;
use Botble\Language\Models\Language as LanguageModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSpecificationAttributeTranslation extends BaseModel
{
    protected $table = 'ec_product_specification_attribute_translations';

    protected $fillable = [
        'product_id',
        'attribute_id',
        'lang_code',
        'value',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(SpecificationAttribute::class, 'attribute_id');
    }

    public static function getCurrentLanguageCode(?string $languageParam = null): string
    {
        if ($languageParam) {
            return $languageParam;
        }

        $refLang = request()->input('ref_lang');
        if ($refLang) {
            return $refLang;
        }

        $currentLocale = app()->getLocale();

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            $language = LanguageModel::query()
                ->where('lang_locale', $currentLocale)
                ->first();

            if ($language) {
                return $language->lang_code;
            }
        }

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            return Language::getDefaultLocaleCode();
        }

        return $currentLocale;
    }

    public static function isDefaultLanguage(?string $currentLangCode = null): bool
    {
        if (! defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            return true;
        }

        $currentLangCode = $currentLangCode ?: self::getCurrentLanguageCode();

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            $defaultLangCode = Language::getDefaultLocaleCode();

            return $currentLangCode === $defaultLangCode;
        }

        return true;
    }

    public static function isEditingDefaultLanguage(): bool
    {
        if (! defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            return true;
        }

        $refLang = request()->input('ref_lang');

        if (! $refLang) {
            return true;
        }

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            return $refLang == Language::getDefaultLocaleCode();
        }

        return true;
    }

    public static function getTranslatedValue(int $productId, int $attributeId, ?string $langCode = null): ?string
    {
        $langCode = $langCode ?: self::getCurrentLanguageCode();

        return self::query()
            ->where('product_id', $productId)
            ->where('attribute_id', $attributeId)
            ->where('lang_code', $langCode)
            ->value('value');
    }

    public static function getDisplayValue(Product $product, SpecificationAttribute $attribute, ?string $langCode = null): ?string
    {
        $specificationAttribute = $product->specificationAttributes->where('id', $attribute->id)->first();

        $defaultValue = optional($specificationAttribute)->pivot?->value ?: $attribute->default_value;

        if (! in_array($attribute->type, ['text', 'textarea'])) {
            return $defaultValue;
        }

        $langCode = $langCode ?: self::getCurrentLanguageCode();

        if (self::isDefaultLanguage($langCode)) {
            return $defaultValue;
        }

        $translatedValue = self::getTranslatedValue($product->id, $attribute->id, $langCode);

        return $translatedValue ?: $defaultValue;
    }
}

<?php

namespace Botble\Ecommerce\Enums;

use Botble\Base\Supports\Enum;

class SpecificationAttributeFieldType extends Enum
{
    public const TEXT = 'text';

    public const TEXTAREA = 'textarea';

    public const SELECT = 'select';

    public const CHECKBOX = 'checkbox';

    public const RADIO = 'radio';

    protected static $langPath = 'plugins/ecommerce::product-specification.enums.field_types';
}

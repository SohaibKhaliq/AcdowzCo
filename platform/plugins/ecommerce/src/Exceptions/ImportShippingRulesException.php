<?php

namespace Botble\Ecommerce\Exceptions;

use Botble\Base\Contracts\Exceptions\IgnoringReport;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ImportShippingRulesException extends BadRequestException implements IgnoringReport
{
}

<?php

namespace Botble\Ecommerce\Http\Requests\Fronts;

use Botble\Ecommerce\Enums\OrderCancellationReasonEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CancelOrderRequest extends Request
{
    public function rules(): array
    {
        return [
            'cancellation_reason' => ['required', Rule::in(OrderCancellationReasonEnum::values())],
            'cancellation_reason_description' => [
                'nullable',
                Rule::requiredIf(fn () => $this->input('cancellation_reason') == OrderCancellationReasonEnum::OTHER),
                'string',
                'min:3',
                'max:255',
            ],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'cancellation_reason' => [
                'description' => 'The reason for cancellation. Must be one of the values from OrderCancellationReasonEnum.',
                'example' => OrderCancellationReasonEnum::CHANGED_MIND,
            ],
            'cancellation_reason_description' => [
                'description' => 'Additional description for the cancellation reason. Required when cancellation_reason is "other".',
                'example' => 'I found a better deal elsewhere.',
            ],
        ];
    }
}

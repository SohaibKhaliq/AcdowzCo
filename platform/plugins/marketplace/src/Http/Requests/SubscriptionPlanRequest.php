<?php

namespace Botble\Marketplace\Http\Requests;

use Botble\Support\Http\Requests\Request;

class SubscriptionPlanRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'duration' => 'required|in:weekly,monthly',
            'price' => 'required|numeric|min:0',
            'priority_boost' => 'nullable|boolean',
            'verified_eligible' => 'nullable|boolean',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|boolean',
        ];
    }
}

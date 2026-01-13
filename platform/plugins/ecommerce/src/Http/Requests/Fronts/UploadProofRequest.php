<?php

namespace Botble\Ecommerce\Http\Requests\Fronts;

use Botble\Support\Http\Requests\Request;

class UploadProofRequest extends Request
{
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:2048'],
        ];
    }

    /**
     * Get the body parameters for API documentation.
     */
    public function bodyParameters(): array
    {
        return [
            'file' => [
                'description' => 'The payment proof file (jpeg, jpg, png, pdf, max 2MB).',
                'type' => 'file',
            ],
        ];
    }
}

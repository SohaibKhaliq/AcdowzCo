<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Support\Http\Requests\Request;

class UpdateCartRequest extends Request
{
    public function rules(): array
    {
        $rules = [];
        $items = $this->input('items', []);

        foreach (array_keys($items) as $rowId) {
            $rules = array_merge($rules, [
                'items.' . $rowId . '.rowId' => ['required', 'string', 'min:6', 'max:255'],
                'items.' . $rowId . '.values' => ['required', 'array'],
                'items.' . $rowId . '.values.qty' => ['required', 'integer', 'min:1', 'max:99999'],
            ]);
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [];
        $items = $this->input('items', []);

        foreach (array_keys($items) as $rowId) {
            $messages = array_merge($messages, [
                'items.' . $rowId . '.rowId.required' => __('Please select a product to add to cart'),
                'items.' . $rowId . '.rowId.string' => __('Something went wrong. Please try again'),
                'items.' . $rowId . '.rowId.min' => __('Something went wrong. Please try again'),
                'items.' . $rowId . '.rowId.max' => __('Something went wrong. Please try again'),
                'items.' . $rowId . '.values.required' => __('Please select product options'),
                'items.' . $rowId . '.values.array' => __('Something went wrong. Please try again'),
                'items.' . $rowId . '.values.qty.required' => __('Please enter the quantity you want to buy'),
                'items.' . $rowId . '.values.qty.integer' => __('Please enter a valid number for quantity'),
                'items.' . $rowId . '.values.qty.min' => __('You must buy at least 1 item'),
                'items.' . $rowId . '.values.qty.max' => __('Sorry, you cannot buy more than 99,999 items at once'),
            ]);
        }

        return $messages;
    }
}

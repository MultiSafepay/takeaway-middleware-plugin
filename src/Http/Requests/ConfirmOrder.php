<?php

declare(strict_types=1);

namespace TakeawayPlugin\Http\Requests;

class ConfirmOrder extends Request
{
    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'orderKey' => 'required|uuid',
            'id' => 'required|uuid',
        ];
    }
}

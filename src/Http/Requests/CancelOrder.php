<?php

declare(strict_types=1);

namespace TakeawayPlugin\Http\Requests;

class CancelOrder extends Request
{
    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'reason' => 'required|string',
            'orderKey' => 'required|uuid',
            'id' => 'required|uuid',
        ];
    }
}

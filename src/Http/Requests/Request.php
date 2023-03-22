<?php

declare(strict_types=1);

namespace TakeawayPlugin\Http\Requests;

use Illuminate\Contracts\Validation\Validator;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator): void
    {
        $message = '';
        $errors = $validator->errors();

        if ($errors->count()) {
            foreach ($errors->getMessages() as $field => $error) {
                $message .= $field.': '.$error[0].' ';
            }
        }

        throw new Exception($message, Response::HTTP_BAD_REQUEST);
    }
}

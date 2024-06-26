<?php

namespace App\Http\Requests;

use OpenApi\Annotations as OA;

class SignUpRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'phone_number' => ['required', 'numeric', 'digits:11', 'unique:users,phone_number'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6']
        ];
    }
}

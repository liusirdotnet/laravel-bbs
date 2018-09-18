<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiRequest;

class ReplyRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => 'required|min:6'
        ];
    }
}

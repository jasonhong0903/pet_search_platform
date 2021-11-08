<?php

namespace App\Validators;

use App\Exceptions\ApiException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

abstract class BaseValidator
{
    protected function throwException($validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(response()->json([
            'status' => ApiException::VALIDATOR_ERROR,
            'msg' => ApiException::$error_message[ApiException::VALIDATOR_ERROR],
            'errors' => Arr::collapse($errors->messages())
        ], JsonResponse::HTTP_OK));
    }
}
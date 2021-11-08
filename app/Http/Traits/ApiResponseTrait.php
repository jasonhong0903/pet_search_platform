<?php


namespace App\Http\Traits;


use App\Exceptions\ApiException;
use Illuminate\Support\Arr;

trait ApiResponseTrait
{
    protected function response($data = [], $status = 200, $pagination = [], ...$extends)
    {
        $response['data'] = $data;
        $response['status'] = $status;

        if (!empty($pagination))
            $response['pagination'] = $pagination;

        $response = array_merge($response, Arr::collapse($extends));

        return response()->json($response);
    }

    protected function makeResponse($status, $status_code = 200, $data = [], $errors = [])
    {
        $ret = [
            'status' => $status,
            'message' => ApiException::$error_message[$status]
        ];

        if (!empty($data)) {
            $ret['data'] = $data;
        }

        if (!empty($errors))
            $ret['errors'] = $errors;

        return response()->json($ret, $status_code);
    }
}
<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController
{

    public function validate(Request $request, array $rules)
    {
        return Validator::make($request->all(), $rules);

    }

    public function response($data, array $headers = [], $message = null, $statusCode = 200)
    {
        // Only handle object.
        if (is_object($data)) {
            if ($data instanceof \Illuminate\Validation\Validator) {
                $data = [
                    'error' => [
                        'message' => $data->getMessageBag()->all()
                    ]
                ];

            }
        }

        // Default format
        $payLoad = [
            'status' => 0,
            'errorCode' => rand(1, 100),
            'message' => 'Please imput proper data.',
            'data' => []
        ];

        // Only handle array.
        if (!empty($data) && is_array($data) && !isset($data['error'])) {
            if (isset($data['message'])) {
                $message = $data['message'];
                unset($data['message']);
                $payLoad['message'] = $message;
            } else {
                $payLoad['message'] = null;
            }

            $payLoad['status'] = 1;
            $payLoad['errorCode'] = null;
            $payLoad['data'] = $data;
        } else {
            if (isset($data['error']) && !empty($data['error'])) {
                $message = isset($data['error']['message']) ? $data['error']['message'] : null;
                $errorCode = isset($data['error']['status_code']) ? $data['error']['status_code'] : null;
            } else {
                $message = null;
                $errorCode = $statusCode;
                $payLoad['status'] = 1;
            }

            unset($data['message']);
            $payLoad['message'] = $message;
            $payLoad['errorCode'] = $errorCode;
        }
        return response()->json($payLoad, $statusCode, $headers);
    }
}

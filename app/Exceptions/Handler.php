<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($this->shouldReport($exception)) {

            //Check to see if LERN is installed otherwise you will not get an exception.
            if (app()->bound("lern")) {
//                app()->make("lern")->handle($exception); //Record and Notify the Exception
                app()->make("lern")->record($exception); //Record the Exception to the database
                /*
                OR...
                app()->make("lern")->record($exception); //Record the Exception to the database
                app()->make("lern")->notify($exception); //Notify the Exception
                */
            }
        }


        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ( $request->wantsJson() && strpos($request->url(), '/api/') !== false) {
            \Log::debug('API Request Exception - '.$request->url().' - '.$exception->getMessage().(! empty($request->all()) ? ' - '.json_encode($request->except(['password'])) : ''));
        }

        return parent::render($request, $exception);
    }

    /**
     * Respond.
     *
     * {
     * "status": false,
     * "errorCode": "INVELID_EMAIL",
     * "message": "Please enter valid email address"
     * "data" : []
     * }
     * @param array $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        $payLoad = [
            'status' => 0,
            'errorCode' => rand(1, 100),
            'message' => 'Please enter valid email address',
            'data' => []
        ];

        if (!empty($data) && !isset($data['error'])) {
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
                $errorCode = $this->getStatusCode();
            }

            unset($data['message']);
            $payLoad['message'] = $message;
            $payLoad['errorCode'] = $errorCode;
        }
        return response()->json($payLoad, $this->getStatusCode(), $headers);
    }
}

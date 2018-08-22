<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;


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
        if ( strpos($request->url(), '/api/') !== false) {
            \Log::debug('API Request Exception - '.$request->url().' - '.$exception->getMessage().(! empty($request->all()) ? ' - '.json_encode($request->except(['password'])) : ''));

            if ($exception instanceof MethodNotAllowedHttpException) {
                return $this->setStatusCode(403)->respondWithError('Please check HTTP Request Method. - MethodNotAllowedHttpException');
            }

            if ($exception instanceof NotFoundHttpException) {
                return $this->setStatusCode(403)->respondWithError('Please check your URL to make sure request is formatted properly. - NotFoundHttpException');
            }

            if ($exception instanceof GeneralException) {
                return $this->setStatusCode(403)->respondWithError($exception->getMessage());
            }

            if ($exception instanceof ModelNotFoundException) {
                return $this->setStatusCode(403)->respondWithError('Item could not be found. Please check identifier.');
            }

            if ($exception instanceof ValidationException) {
                \Log::debug('API Validation Exception - '.json_encode($exception->validator->messages()));

                return $this->setStatusCode(422)->respondWithError($exception->validator->messages());
            }


        }

        return parent::render($request, $exception);
    }


    /**
     * get the status code.
     *
     * @return statuscode
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * set the status code.
     *
     * @param [type] $statusCode [description]
     *
     * @return statuscode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }


    protected function respondWithError($message)
    {
        return $this->respond(
            [
                'error' => [
                    'message'     => $message,
                    'status_code' => $this->getStatusCode(),
                ],
            ]
        );
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
                $errorCode = 404;
            }

            unset($data['message']);
            $payLoad['message'] = $message;
            $payLoad['errorCode'] = $errorCode;
        }
        return response()->json($payLoad, 404, $headers);
    }
}

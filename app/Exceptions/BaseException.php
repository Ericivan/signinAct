<?php

namespace App\Exceptions;

use Exception;

class BaseException extends Exception
{
    public $httpCode;

    public $message;


    public $headers;


    public function __construct($code, $httpCode, $message = null, $headers = [])
    {
        $this->code = $code;

        $this->httpCode = $httpCode;

        $this->message = $message;

        $this->headers = $headers;

    }

    public function render($request)
    {
        return response([
            'code' => $this->code,
            'message' => $this->message
        ], $this->httpCode, $this->headers);
    }
}

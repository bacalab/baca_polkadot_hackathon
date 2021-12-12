<?php


namespace App\Exceptions;

use Exception;
use Throwable;

class NormalExcept extends Exception
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->message = $message;
        $this->code = $code;
    }

    public function render($request)
    {
        return response()->json([
            'code' => $this->getCode(),
            'message' => $this->getMessage()
        ], 403);
    }
}

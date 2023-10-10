<?php

namespace Lqc\MiddleOfficeFileUpload\Exception;

use Exception;

class UploadException extends Exception
{
    /**
     * @param $errorMessage
     * @param $errorCode
     * @param $previous
     */
    public function __construct($errorMessage, $errorCode = 0, $previous = null)
    {
        parent::__construct($errorMessage, $errorCode, $previous);
    }
}
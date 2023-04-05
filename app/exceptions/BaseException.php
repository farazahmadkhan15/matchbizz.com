<?php

namespace App\Exceptions;
use \Exception;

class BaseException extends Exception
{
    protected $codeName = "";

    public function __construct(string $codeName, string $message = null, Exception $previous = null) {
        $this->codeName = $codeName;

        parent::__construct($message, 0, $previous);
    }

    public function getCodeName() {
        return $this->codeName;
    }
}
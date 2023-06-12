<?php

namespace App\DataTransferObject\Response;

use App\Contracts\ServiceResponseInterface;

class ErrorResponse implements ServiceResponseInterface
{
    private string $message;
    private string $code;

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return ErrorResponse
     */
    public function setMessage(string $message): ErrorResponse
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return ErrorResponse
     */
    public function setCode(string $code): ErrorResponse
    {
        $this->code = $code;
        return $this;
    }

}
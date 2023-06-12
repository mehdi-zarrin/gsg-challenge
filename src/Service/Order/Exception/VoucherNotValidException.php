<?php

namespace App\Service\Order\Exception;

class VoucherNotValidException extends \Exception
{
    protected $message = 'Provided voucher code is not valid';
}
<?php

namespace App\Service\Order\Exception;

class VoucherAlreadyUsedException extends \Exception
{
    protected $message = 'This voucher code is already used.';
}
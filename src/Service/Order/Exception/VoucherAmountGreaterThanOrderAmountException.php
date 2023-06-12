<?php

namespace App\Service\Order\Exception;

use Exception;

class VoucherAmountGreaterThanOrderAmountException extends Exception
{
    protected $message = 'This voucher can not be used for this order because the discount amount is greater than the order amount';
}
<?php

namespace App\Service\Voucher\Exception;

class VoucherNotFoundException extends \Exception
{
    protected $message = 'There is no such a voucher or the voucher is expired';
}
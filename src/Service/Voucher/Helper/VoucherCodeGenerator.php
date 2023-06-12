<?php

namespace App\Service\Voucher\Helper;

class VoucherCodeGenerator
{
    /**
     * @return string
     */
    public function generate(): string
    {
        return strtoupper(substr(md5(mt_rand()), 0, 4));
    }
}
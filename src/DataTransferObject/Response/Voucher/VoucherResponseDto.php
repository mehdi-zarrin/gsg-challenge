<?php

namespace App\DataTransferObject\Response\Voucher;

use App\Contracts\ServiceResponseInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;

class VoucherResponseDto implements ServiceResponseInterface
{
    #[SerializedName('valid_until')]
    private string $validUntil;
    private string $code;
    private int $amount;

    /**
     * @return string
     */
    public function getValidUntil(): string
    {
        return $this->validUntil;
    }

    /**
     * @param string $validUntil
     * @return VoucherResponseDto
     */

    public function setValidUntil(string $validUntil): VoucherResponseDto
    {
        $this->validUntil = $validUntil;
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
     * @return VoucherResponseDto
     */
    public function setCode(string $code): VoucherResponseDto
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return VoucherResponseDto
     */
    public function setAmount(int $amount): VoucherResponseDto
    {
        $this->amount = $amount;
        return $this;
    }
}
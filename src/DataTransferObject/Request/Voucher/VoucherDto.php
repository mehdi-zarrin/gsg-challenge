<?php

namespace App\DataTransferObject\Request\Voucher;

use App\Contracts\ServiceRequestInterface;
use DateTime;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class VoucherDto implements ServiceRequestInterface
{
    #[Assert\NotBlank]
    private DateTimeInterface $validUntil;

    #[Assert\NotBlank]
    #[Assert\Positive]
    private int $amount;

    /**
     * @return DateTimeInterface
     */
    public function getValidUntil(): DateTimeInterface
    {
        return $this->validUntil;
    }

    /**
     * @param string $validUntil
     * @return VoucherDto
     */
    public function setValidUntil(string $validUntil): VoucherDto
    {
        $this->validUntil = new DateTime($validUntil);

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
     * @return VoucherDto
     */
    public function setAmount(int $amount): VoucherDto
    {
        $this->amount = $amount;

        return $this;
    }
}

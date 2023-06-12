<?php

namespace App\DataTransferObject\Request\Order;

use App\Contracts\ServiceRequestInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class OrderDto implements ServiceRequestInterface
{
    #[NotBlank]
    private int $amount;
    private ?string $voucherCode = null;

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return OrderDto
     */
    public function setAmount(int $amount): OrderDto
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVoucherCode(): ?string
    {
        return $this->voucherCode;
    }

    /**
     * @param string|null $voucherCode
     * @return OrderDto
     */
    public function setVoucherCode(?string $voucherCode): OrderDto
    {
        $this->voucherCode = $voucherCode;

        return $this;
    }
}
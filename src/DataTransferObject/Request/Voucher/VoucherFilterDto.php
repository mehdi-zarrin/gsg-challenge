<?php

namespace App\DataTransferObject\Request\Voucher;

use App\Contracts\ServiceRequestInterface;

class VoucherFilterDto implements ServiceRequestInterface
{
    private ?string $state = null;

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string|null $state
     * @return VoucherFilterDto
     */
    public function setState(?string $state): VoucherFilterDto
    {
        $this->state = $state;
        return $this;
    }

}
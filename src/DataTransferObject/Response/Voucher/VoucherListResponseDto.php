<?php

namespace App\DataTransferObject\Response\Voucher;

use App\Contracts\ServiceResponseInterface;

class VoucherListResponseDto implements ServiceResponseInterface
{
    /**
     * @var VoucherResponseDto[]
     */
    private array $items;

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @return VoucherListResponseDto
     */
    public function setItems(array $items): VoucherListResponseDto
    {
        $this->items = $items;

        return $this;
    }
}

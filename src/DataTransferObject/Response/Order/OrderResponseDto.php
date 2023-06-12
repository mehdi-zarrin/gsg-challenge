<?php

namespace App\DataTransferObject\Response\Order;

use App\Contracts\ServiceResponseInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;

class OrderResponseDto implements ServiceResponseInterface
{
    private int $subtotal;
    #[SerializedName('discount_total')]
    private int $discountTotal;
    #[SerializedName('grand_total')]
    private int $grandTotal;
    #[SerializedName('purchase_date')]
    private string $purchaseDate;

    /**
     * @return int
     */
    public function getSubtotal(): int
    {
        return $this->subtotal;
    }

    /**
     * @param int $subtotal
     * @return OrderResponseDto
     */
    public function setSubtotal(int $subtotal): OrderResponseDto
    {
        $this->subtotal = $subtotal;
        return $this;
    }

    /**
     * @return int
     */
    public function getDiscountTotal(): int
    {
        return $this->discountTotal;
    }

    /**
     * @param int $discountTotal
     * @return OrderResponseDto
     */
    public function setDiscountTotal(int $discountTotal): OrderResponseDto
    {
        $this->discountTotal = $discountTotal;
        return $this;
    }

    /**
     * @return int
     */
    public function getGrandTotal(): int
    {
        return $this->grandTotal;
    }

    /**
     * @param int $grandTotal
     * @return OrderResponseDto
     */
    public function setGrandTotal(int $grandTotal): OrderResponseDto
    {
        $this->grandTotal = $grandTotal;
        return $this;
    }

    /**
     * @return string
     */
    public function getPurchaseDate(): string
    {
        return $this->purchaseDate;
    }

    /**
     * @param string $purchaseDate
     * @return OrderResponseDto
     */
    public function setPurchaseDate(string $purchaseDate): OrderResponseDto
    {
        $this->purchaseDate = $purchaseDate;
        return $this;
    }
}
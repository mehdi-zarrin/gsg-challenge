<?php

namespace App\DataTransferObject\Response\Order;

use App\Contracts\ServiceResponseInterface;

class OrderListResponseDto implements ServiceResponseInterface
{
    /**
     * @var OrderResponseDto[] $items
     */
    private array $items;
    private ?array $links;

    /**
     * @return OrderResponseDto[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @return OrderListResponseDto
     */
    public function setItems(array $items): OrderListResponseDto
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @return ?array
     */
    public function getLinks(): ?array
    {
        return $this->links;
    }

    /**
     * @param array $links
     * @return $this
     */
    public function setLinks(array $links): OrderListResponseDto
    {
        $this->links = $links;

        return $this;
    }
}
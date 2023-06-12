<?php

namespace App\DataTransferObject\Request\Order;

use App\Contracts\ServiceRequestInterface;

class OrderListDto implements ServiceRequestInterface
{
    private string $page = "1";

    /**
     * @return string
     */
    public function getPage(): string
    {
        return $this->page;
    }

    /**
     * @param string $page
     * @return OrderListDto
     */
    public function setPage(string $page): OrderListDto
    {
        $this->page = $page;
        return $this;
    }
}
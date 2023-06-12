<?php

namespace App\Service\Order\Hydrator;

use App\Constants\ApplicationConstants;
use App\DataTransferObject\Response\Order\OrderListResponseDto;
use App\DataTransferObject\Response\Order\OrderResponseDto;
use App\Entity\Order;
use App\Service\ApiCollection\CollectionDto;

class OrderHydrator
{
    /**
     * @param CollectionDto $collectionDto
     * @return OrderListResponseDto
     */
    public function hydrateCollection(CollectionDto $collectionDto)
    {
        $items = [];
        /** @var Order $item */
        foreach ($collectionDto->getItems() as $item) {
            $items[] = (new OrderResponseDto())
                ->setSubtotal($item->getSubtotal())
                ->setDiscountTotal($item->getDiscountTotal())
                ->setGrandTotal($item->getGrandTotal())
                ->setPurchaseDate($item->getCreatedAt()->format(ApplicationConstants::MYSQL_DATETIME_FORMAT));
        }

        return (new OrderListResponseDto())
            ->setItems($items)
            ->setLinks($collectionDto->getLinks());
    }
}
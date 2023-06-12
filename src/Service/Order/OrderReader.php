<?php

namespace App\Service\Order;

use App\Contracts\ServiceRequestInterface;
use App\DataTransferObject\Request\Order\OrderListDto;
use App\Repository\OrderRepository;
use App\Service\ApiCollection\ORMCollectionBuilder;
use App\Service\Order\Hydrator\OrderHydrator;

class OrderReader
{
    private OrderRepository $repository;
    protected ORMCollectionBuilder $collectionBuilder;
    private OrderHydrator $orderHydrator;
    protected int $orderListItemsPerPage;


    public function __construct(
        OrderRepository $repository,
        ORMCollectionBuilder $collectionBuilder,
        OrderHydrator $orderHydrator,
        int $orderListItemsPerPage
    ) {
        $this->repository = $repository;
        $this->collectionBuilder = $collectionBuilder;
        $this->orderHydrator = $orderHydrator;
        $this->orderListItemsPerPage = $orderListItemsPerPage;
    }


    /**
     * @param OrderListDto $serviceRequest
     */
    public function getList(ServiceRequestInterface $serviceRequest)
    {
        $adapter = $this->collectionBuilder->configure(
            $this->repository->getListQueryBuilder(),
            'orders_list',
            maxPerPage: $this->orderListItemsPerPage,
        );
        $collection = $this->collectionBuilder->build(
            $adapter,
            page: $serviceRequest->getPage(),
        );

        return $this->orderHydrator->hydrateCollection($collection);
    }
}

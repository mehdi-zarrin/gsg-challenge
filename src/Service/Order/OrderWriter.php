<?php

namespace App\Service\Order;

use App\Constants\ApplicationConstants;
use App\Contracts\ServiceRequestInterface;
use App\DataTransferObject\Request\Order\OrderDto;
use App\DataTransferObject\Response\Order\OrderResponseDto;
use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Service\Order\Exception\VoucherAlreadyUsedException;
use App\Service\Order\Exception\VoucherAmountGreaterThanOrderAmountException;
use App\Service\Order\Exception\VoucherNotValidException;
use App\Service\Voucher\VoucherReader;
use App\Service\Voucher\VoucherWriter;

class OrderWriter
{
    protected OrderRepository $repository;
    protected VoucherReader $voucherReader;
    protected VoucherWriter $voucherWriter;

    public function __construct(OrderRepository $repository, VoucherReader $voucherReader, VoucherWriter $voucherWriter)
    {
        $this->repository = $repository;
        $this->voucherReader = $voucherReader;
        $this->voucherWriter = $voucherWriter;
    }

    /**
     * @param OrderDto $serviceRequest
     */
    public function create(ServiceRequestInterface $serviceRequest)
    {
        $discountTotal = 0;
        if ($serviceRequest->getVoucherCode()) {
            $voucherEntity = $this->voucherReader->getActiveVoucherByCode($serviceRequest->getVoucherCode());

            if (!$voucherEntity) {
                throw new VoucherNotValidException();
            }

            if ($voucherEntity->getAmount() > $serviceRequest->getAmount()) {
                throw new VoucherAmountGreaterThanOrderAmountException();
            }

            if (!$voucherEntity->getIsActive()) {
                throw new VoucherAlreadyUsedException();
            }

            $discountTotal = $voucherEntity->getAmount();
            $this->voucherWriter->invalidateVoucher($voucherEntity);
        }

        $orderEntity = (new Order())
            ->setSubtotal($serviceRequest->getAmount())
            ->setDiscountTotal($discountTotal)
            ->setGrandTotal($serviceRequest->getAmount() - $discountTotal);

        $this->repository->save($orderEntity, flush: true);
        return (new OrderResponseDto())
            ->setSubtotal($orderEntity->getSubtotal())
            ->setDiscountTotal($orderEntity->getDiscountTotal())
            ->setGrandTotal($orderEntity->getGrandTotal())
            ->setPurchaseDate($orderEntity->getCreatedAt()->format(ApplicationConstants::MYSQL_DATETIME_FORMAT));
    }
}
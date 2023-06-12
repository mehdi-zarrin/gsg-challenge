<?php

namespace App\Service\Voucher;

use App\Contracts\ServiceRequestInterface;
use App\DataTransferObject\Request\Voucher\VoucherDto;
use App\DataTransferObject\Request\Voucher\VoucherFilterDto;
use App\DataTransferObject\Response\Voucher\VoucherResponseDto;
use App\DataTransferObject\Response\Voucher\VoucherListResponseDto;
use App\Entity\Voucher;
use App\Repository\VoucherRepository;

class VoucherReader
{
    protected VoucherRepository $repository;

    public function __construct(VoucherRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param VoucherFilterDto $request
     */
    public function handle(ServiceRequestInterface $voucherFilterDto)
    {
        $result = $this->repository->getVoucherList($voucherFilterDto);
        $items = [];
        /** @var Voucher $item */
        foreach ($result as $item) {
            $items[] = (new VoucherResponseDto())
                ->setAmount($item->getAmount())
                ->setCode($item->getCode())
                ->setValidUntil($item->getValidUntil()->format('Y-m-d H:i:s'));
        }

        return (new VoucherListResponseDto())->setItems($items);
    }

    /**
     * @param string $code
     * @return Voucher|null
     */
    public function getActiveVoucherByCode(string $code): ?Voucher
    {
        $voucherEntity = $this->repository->findActiveVoucherByCode($code);
        if (!$voucherEntity) {
            return null;
        }

        return $voucherEntity;
    }
}

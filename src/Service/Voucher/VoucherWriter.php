<?php

namespace App\Service\Voucher;

use App\Constants\ApplicationConstants;
use App\Contracts\ServiceRequestInterface;
use App\Contracts\ServiceResponseInterface;
use App\DataTransferObject\Request\Voucher\VoucherDto;
use App\DataTransferObject\Response\Voucher\VoucherResponseDto;
use App\Entity\Voucher;
use App\Repository\VoucherRepository;
use App\Service\Voucher\Exception\VoucherNotFoundException;
use App\Service\Voucher\Helper\VoucherCodeGenerator;

class VoucherWriter
{
    protected VoucherRepository $repository;

    /**
     * @var VoucherCodeGenerator
     */
    protected $voucherCodeGenerator;

    public function __construct(VoucherRepository $repository, VoucherCodeGenerator $voucherCodeGenerator)
    {
        $this->repository = $repository;
        $this->voucherCodeGenerator = $voucherCodeGenerator;
    }

    /**
     * @param VoucherDto $serviceRequest
     * @return ServiceResponseInterface
     */
    public function create(ServiceRequestInterface $serviceRequest): ServiceResponseInterface
    {
        $entity = (new Voucher())
            ->setAmount($serviceRequest->getAmount())
            ->setCode($this->voucherCodeGenerator->generate())
            ->setValidUntil($serviceRequest->getValidUntil());

        $this->repository->save($entity, flush: true);
        return (new VoucherResponseDto())
            ->setValidUntil($entity->getValidUntil()->format(ApplicationConstants::MYSQL_DATETIME_FORMAT))
            ->setCode($entity->getCode())
            ->setAmount($entity->getAmount());
    }

    /**
     * @param VoucherDto $serviceRequest
     * @return ServiceResponseInterface
     */
    public function update(ServiceRequestInterface $serviceRequest, int $id): ServiceResponseInterface
    {
        $voucher = $this->repository->findActiveVoucherById($id);
        if (!$voucher) {
            throw new VoucherNotFoundException();
        }

        $voucher->setValidUntil($serviceRequest->getValidUntil())
            ->setAmount($serviceRequest->getAmount());

        $this->repository->save($voucher, flush: true);

        return (new VoucherResponseDto())
            ->setValidUntil($voucher->getValidUntil()->format(ApplicationConstants::MYSQL_DATETIME_FORMAT))
            ->setCode($voucher->getCode())
            ->setAmount($voucher->getAmount());
    }

    public function delete(int $id)
    {
        $voucher = $this->repository->findOneBy([
            'id' => $id
        ]);
        if (!$voucher) {
            throw new VoucherNotFoundException();
        }

        $this->repository->remove($voucher, flush: true);
    }

    public function invalidateVoucher(Voucher $voucher)
    {
        $voucher->setIsActive(false);
        $this->repository->save($voucher, flush: true);
    }
}

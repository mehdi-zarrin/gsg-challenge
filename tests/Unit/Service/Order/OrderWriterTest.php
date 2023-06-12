<?php

namespace App\Tests\Unit\Service\Order;

use App\DataTransferObject\Request\Order\OrderDto;
use App\Service\Order\Exception\VoucherAlreadyUsedException;
use App\Service\Order\Exception\VoucherAmountGreaterThanOrderAmountException;
use App\Service\Order\Exception\VoucherNotValidException;
use App\Service\Order\OrderWriter;
use App\Tests\Common\DatabaseTestCase;

class OrderWriterTest extends DatabaseTestCase
{
    /**
     * @test
     */
    public function it_guards_against_already_used_voucher_use()
    {
        $this->expectException(VoucherAlreadyUsedException::class);

        $this->loader->load(['tests/fixtures/voucher/already_used_voucher.yml']);
        /** @var OrderWriter $orderWriter */
        $orderWriter = $this->getContainer()->get('App\Service\Order\OrderWriter');
        $request = (new OrderDto())
            ->setAmount(10)
            ->setVoucherCode('1234');
        
        $orderWriter->create($request);
    }


    /**
     * @test
     */
    public function it_guards_against_greater_voucher_amount_than_order_amount()
    {
        $this->expectException(VoucherAmountGreaterThanOrderAmountException::class);

        $this->loader->load(['tests/fixtures/voucher/high_amount_voucher.yml']);
        /** @var OrderWriter $orderWriter */
        $orderWriter = $this->getContainer()->get('App\Service\Order\OrderWriter');
        $request = (new OrderDto())
            ->setAmount(10)
            ->setVoucherCode('1234');

        $orderWriter->create($request);
    }

    /**
     * @test
     */
    public function it_guards_against_expired_voucher()
    {
        $this->expectException(VoucherNotValidException::class);

        $this->loader->load(['tests/fixtures/voucher/expired_voucher.yml']);
        /** @var OrderWriter $orderWriter */
        $orderWriter = $this->getContainer()->get('App\Service\Order\OrderWriter');
        $request = (new OrderDto())
            ->setAmount(10)
            ->setVoucherCode('1234');

        $orderWriter->create($request);
    }


}
<?php

namespace App\Tests\Unit\Service\ApiCollection;

use App\Repository\VoucherRepository;
use App\Service\ApiCollection\CollectionDto;
use App\Service\ApiCollection\ORMCollectionBuilder;
use App\Tests\Common\DatabaseTestCase;
use Symfony\Component\Routing\RouterInterface;

class ORMCollectionBuilderTest extends DatabaseTestCase
{
    /** @test */
    public function it_could_paginate_any_resource()
    {
        $this->loader->load(['tests/fixtures/vouchers.yml']);

        $mockRouter = $this->getMockBuilder(RouterInterface::class)->getMock();
        $mockRouter->method('generate')->willReturn('dummyRoute');
        $collectionBuilder = new ORMCollectionBuilder($mockRouter);

        /** @var VoucherRepository $voucherRepository */
        $voucherRepository = $this->getContainer()->get('App\Repository\VoucherRepository');
        $queryBuilder = $voucherRepository->createQueryBuilder('v');
        $adapter = $collectionBuilder->configure($queryBuilder, 'nnn', maxPerPage: 1);
        $result = $collectionBuilder->build($adapter, 1);

        $this->assertInstanceOf(CollectionDto::class, $result);
        $this->assertCount(1, $result->getItems());
        $this->assertArrayHasKey('next', $result->getLinks());
        $this->assertArrayNotHasKey('prev', $result->getLinks());
    }
}
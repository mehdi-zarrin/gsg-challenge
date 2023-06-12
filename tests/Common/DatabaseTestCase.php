<?php

namespace App\Tests\Common;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\Tools\SchemaTool;
use Fidry\AliceDataFixtures\LoaderInterface;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DatabaseTestCase extends WebTestCase
{
    use RefreshDatabaseTrait;

    protected LoaderInterface $loader;
    protected Registry $doctrine;
    protected KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->loader = $this->getContainer()->get('fidry_alice_data_fixtures.loader.doctrine');
        $this->doctrine = $this->getContainer()->get('doctrine');
    }
}

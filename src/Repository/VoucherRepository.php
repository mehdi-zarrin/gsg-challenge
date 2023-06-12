<?php

namespace App\Repository;

use App\Constants\ApplicationConstants;
use App\Contracts\ServiceRequestInterface;
use App\DataTransferObject\Request\Voucher\VoucherFilterDto;
use App\Entity\Voucher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Voucher>
 *
 * @method Voucher|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voucher|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voucher[]    findAll()
 * @method Voucher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoucherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voucher::class);
    }

    public function save(Voucher $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Voucher $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param VoucherFilterDto $voucherFilterDto
     * @return int|mixed|string
     */
    public function getVoucherList(ServiceRequestInterface $voucherFilterDto)
    {
        $queryBuilder = $this->createQueryBuilder('v')
            ->orderBy('v.validUntil', 'DESC');

        if ($voucherFilterDto->getState() === 'active') {
            $queryBuilder->andWhere('v.isActive = :isActive')
                ->andWhere('v.validUntil >= :currentDate')
                ->setParameters([
                    'isActive' => true,
                    'currentDate' => (new \DateTime())->format(ApplicationConstants::MYSQL_DATETIME_FORMAT)
                ]);
        }

        if ($voucherFilterDto->getState() === 'inactive') {
            $queryBuilder->andWhere('v.isActive = :isActive')
                ->orWhere('v.validUntil <= :currentDate')
                ->setParameters([
                    'isActive' => false,
                    'currentDate' => (new \DateTime())->format(ApplicationConstants::MYSQL_DATETIME_FORMAT)
                ]);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function findActiveVoucherById(int $id)
    {
        return $this->queryActiveVoucher()
            ->andWhere('v.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findActiveVoucherByCode(string $code)
    {
        return $this->createQueryBuilder('v')
            ->where('v.validUntil >= :validUntil')
            ->andWhere('v.code = :code')
            ->setParameters([
                'validUntil' => (new \DateTime())->format(ApplicationConstants::MYSQL_DATETIME_FORMAT),
                'code' => $code
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }

    protected function queryActiveVoucher()
    {
        return $this->createQueryBuilder('v')
            ->where('v.validUntil >= :validUntil')
            ->andWhere('v.isActive = :isActive')
            ->andWhere('v.id = :id')
            ->setParameters([
                'validUntil' => (new \DateTime())->format(ApplicationConstants::MYSQL_DATETIME_FORMAT),
                'isActive' => true,
            ]);
    }
}

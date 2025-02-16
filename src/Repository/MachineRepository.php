<?php

namespace App\Repository;

use App\Entity\Machine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Machine>
 *
 * @method Machine|null find($id, $lockMode = null, $lockVersion = null)
 * @method Machine|null findOneBy(array $criteria, array $orderBy = null)
 * @method Machine[]    findAll()
 * @method Machine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MachineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Machine::class);
    }

    public function save(Machine $entity, bool $flush = false): void
    {
        $entity->setUpdatedAt(new \DateTime());
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Machine $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Machine[] Returns an array of the latest machines
     */
    public function findLatest(int $limit = 5): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Machine[] Returns an array of machines matching the search query
     */
    public function search(string $query): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.name LIKE :query')
            ->orWhere('m.model LIKE :query')
            ->orWhere('m.description LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('m.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
} 
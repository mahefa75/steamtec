<?php

namespace App\Repository;

use App\Entity\TroubleshootingNode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TroubleshootingNode>
 *
 * @method TroubleshootingNode|null find($id, $lockMode = null, $lockVersion = null)
 * @method TroubleshootingNode|null findOneBy(array $criteria, array $orderBy = null)
 * @method TroubleshootingNode[]    findAll()
 * @method TroubleshootingNode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TroubleshootingNodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TroubleshootingNode::class);
    }

    public function save(TroubleshootingNode $entity, bool $flush = false): void
    {
        $entity->setUpdatedAt(new \DateTime());
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TroubleshootingNode $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return TroubleshootingNode[] Returns an array of root nodes (nodes without parents)
     */
    public function findRootNodes(): array
    {
        return $this->createQueryBuilder('n')
            ->where('n.parent IS NULL')
            ->orderBy('n.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return TroubleshootingNode[] Returns an array of nodes matching the search query
     */
    public function search(string $query): array
    {
        return $this->createQueryBuilder('n')
            ->where('n.title LIKE :query')
            ->orWhere('n.description LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('n.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return TroubleshootingNode[] Returns an array of solution nodes for a specific machine
     */
    public function findSolutionsForMachine(int $machineId): array
    {
        return $this->createQueryBuilder('n')
            ->where('n.machine = :machineId')
            ->andWhere('n.isSolution = true')
            ->setParameter('machineId', $machineId)
            ->orderBy('n.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns the complete path from a node to the root
     * @return TroubleshootingNode[]
     */
    public function getPathToRoot(TroubleshootingNode $node): array
    {
        $path = [$node];
        $current = $node;

        while ($current->getParent() !== null) {
            $current = $current->getParent();
            array_unshift($path, $current);
        }

        return $path;
    }
} 
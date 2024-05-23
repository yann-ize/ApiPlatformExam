<?php

namespace App\Repository;

use App\Entity\Mairie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mairie>
 *
 * @method Mairie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mairie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mairie[]    findAll()
 * @method Mairie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MairieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mairie::class);
    }

    public function add(Mairie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Mairie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

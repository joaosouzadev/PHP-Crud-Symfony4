<?php

namespace App\Repository;

use App\Entity\Vendedor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Collections\Collection;

/**
 * @method Vendedor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vendedor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vendedor[]    findAll()
 * @method Vendedor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VendedorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Vendedor::class);
    }

    public function findByEmpresa($id) {

        $querybuilder = $this->createQueryBuilder('e');
        return $querybuilder->select('e')
            ->where('e.empresa IN (:empresa)')
            ->setParameter('empresa', $id)
            ->getQuery()
            ->getResult();
    }
}

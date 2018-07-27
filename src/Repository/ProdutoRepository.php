<?php

namespace App\Repository;

use App\Entity\Produto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Collections\Collection;

/**
 * @method Produto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produto[]    findAll()
 * @method Produto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProdutoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Produto::class);
    }

    public function findByEmpresa(Collection $empresas) {

        $querybuilder = $this->createQueryBuilder('e');
        return $querybuilder->select('e')
            ->where('e.empresa IN (:empresa)')
            ->setParameter('empresa', $empresas)
            ->getQuery()
            ->getResult();
    }
}

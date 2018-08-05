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

    // public function findByEmpresa(Collection $empresas) {

    //     $querybuilder = $this->createQueryBuilder('e');
    //     return $querybuilder->select('e')
    //         ->where('e.empresa IN (:empresa)')
    //         ->setParameter('empresa', $empresas)
    //         ->getQuery()
    //         ->getResult();
    // }

    public function findByEmpresa($id) {

        $querybuilder = $this->createQueryBuilder('e');
        return $querybuilder->select('e')
            ->where('e.empresa IN (:empresa)')
            ->setParameter('empresa', $id)
            ->getQuery()
            ->getResult();
    }

    public function findByCodigo($cod, $empresa) {

        $querybuilder = $this->createQueryBuilder('p');
        return $querybuilder->select('p')
            ->where('p.codigo IN (:codigo)')
            ->andWhere('p.empresa IN (:empresa)')
            ->setParameter('codigo', $cod)
            ->setParameter('empresa', $empresa)
            ->getQuery()
            ->getResult();
    }

    public function findByNome($produtoNome) {

        $querybuilder = $this->createQueryBuilder('p');
        return $querybuilder->select('p')
            ->where('p.nome IN (:nome)')
            ->setParameter('nome', $produtoNome)
            ->getQuery()
            ->getResult();
    }
}

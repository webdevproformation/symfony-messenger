<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Produit>
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry , private EntityManagerInterface $em)
    {
        parent::__construct($registry, Produit::class);
    }
}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Description of ProductRepository
 *
 * @author Nekon
 * @method Product|null find($id)
 * @method Product[]|null findAll()
 */

class ProductRepository extends ServiceEntityRepository {
    //put your code here
    
    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, Product::class);
    }
    
}

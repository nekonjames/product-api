<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

use App\Entity\Discount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Description of DiscountRepository
 *
 * @author Nekon
 */
class DiscountRepository extends ServiceEntityRepository {
    //put your code here
    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, Discount::class);
    }
}

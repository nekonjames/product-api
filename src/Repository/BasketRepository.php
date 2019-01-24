<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

use App\Entity\Basket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Description of BasketRepository
 *
 * @author Nekon
 */
class BasketRepository extends ServiceEntityRepository{
    //put your code here
    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, Basket::class);
    }
}

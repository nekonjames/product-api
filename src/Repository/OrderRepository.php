<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Description of OrderRepository
 * @method Order|null findOneBy(array $criteria)
 * @author Nekon
 */
class OrderRepository extends ServiceEntityRepository{
    //put your code here
    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, Order::class);
    }
    
}

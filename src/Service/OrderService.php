<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use App\Entity\Order;
use App\Entity\Product;
use App\Repository\AccountRepository;
use App\Repository\BasketRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Description of OrderService
 *
 * @author Nekon
 */
class OrderService {
    //put your code here
    private $basketRepository;
    private $entityManager;


    public function __construct(BasketRepository $basketRepository,EntityManagerInterface $entityManager) {
        $this->basketRepository = $basketRepository;
        $this->entityManager = $entityManager;
    }
    
    public function processOrder(UserInterface $account){
        
        $basket = $this->basketRepository->findOneBy(["customerAccount"=>$account]);
        
        if(!$basket || !$basket->getProducts()->count() > 0){
            throw new RuntimeException("Your basket is empty, add product to your basket before placing order");
        }
        
        $order = new Order();
        $order->setCustomerAccount($account);
        $order->setPrice($basket->finalPrice());
        
        $this->entityManager->persist($account);
        $this->entityManager->persist($order);
        $this->entityManager->flush();
        
        foreach ($basket->getProducts() as $basketProduct){
            /** @var Product $basketProduct */
            $order->addProduct($basketProduct);
            $basketProduct->addOrder($order);
            $this->entityManager->persist($basketProduct);
        }
        
        $this->entityManager->persist($order);
        $this->entityManager->remove($basket);
        $this->entityManager->flush();
        
        return $order;
    }
}

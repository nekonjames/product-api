<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use App\Entity\Basket;
use App\Entity\Product;
use App\Repository\AccountRepository;
use App\Repository\BasketRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Description of BasketService
 *
 * @author Nekon
 */
class BasketService {
    //put your code here
    private $basketRepository;
    private $accountRepository;
    private $productRepository;
    private $entityManager;


    public function __construct(BasketRepository $basketRepository, ProductRepository $productRepository, AccountRepository $accountRepository,EntityManagerInterface $entityManager) {
        $this->basketRepository = $basketRepository;
        $this->accountRepository = $accountRepository;
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
    }
    
    public function processBasket(Product $product, UserInterface $account){        
                
        $basket = $this->basketRepository->findOneBy(["customerAccount"=>$account]);
        if(!$basket){
            $basket = new Basket();            
        }
        $basket->setCustomerAccount($account);
                
        foreach ($basket->getProducts() as $basketProduct){
            /** 
             * @var Product $basketProduct 
             */
            if($basketProduct->getId() == $product->getId()){
                $data = array('error'=>TRUE,'message' => 'Product already in the basket');
                return $data;
            }
        }
        
        $basket->addProduct($product);
        $product->addBasket($basket);        
        
        $this->entityManager->persist($product);
        $this->entityManager->persist($basket);        
        
        $this->entityManager->flush();        
        
        
        return $basket;
    }
}

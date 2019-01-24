<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Entity\Product;
use App\Service\BasketService;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Description of BasketController
 *
 * @author Nekon
 */
class BasketController extends AbstractController{
    //put your code here
    private $serializer;
    private $basketService;


    public function __construct(BasketService $basketService, SerializerInterface $serializer) {
        $this->serializer = $serializer;
        $this->basketService = $basketService;
    }
    
    /**
     * @Route("/api/basket/product/{id}", methods="PUT")
     */
    public function addToBasket(Product $product, UserInterface $account = null){        
        
        if(!$account){
            throw new UsernameNotFoundException("User must log in");
        }        
        $basket = $this->basketService->processBasket($product, $account);
        
        return JsonResponse::fromJsonString($this->serializer->serialize($basket,"json"));
    }
}

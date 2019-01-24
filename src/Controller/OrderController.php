<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Service\OrderService;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Description of OrderController
 *
 * @author Nekon
 */
class OrderController extends AbstractController{
    //put your code here
    private $serializer;
    private $orderService;


    public function __construct(OrderService $orderService, SerializerInterface $serializer) {
        $this->orderService = $orderService;
        $this->serializer = $serializer;
    }
    /**
     * @Route("/api/order/checkout", methods="POST")
     */
    public function doCheckOut(UserInterface $account = null){
        
        if(!$account){
            throw new UsernameNotFoundException("User must log in");
        }           
        
        $order = $this->orderService->processOrder($account);
        
        return JsonResponse::fromJsonString($this->serializer->serialize($order,"json"));
    }
}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;

use App\Service\ProductService;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Description of ProductController
 *
 * @author Nekon
 */
class ProductController extends AbstractController {
    //put your code here
    private $productService;
    private $serializer;
    
    public function __construct(ProductService $productService, SerializerInterface $serializer) {
        $this->productService = $productService;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/product/products", methods="GET")
     */
    public function getAllProducts(){
        $products = $this->productRepository->findAll();
        return JsonResponse::fromJsonString($this->serializer->serialize($products,'json'));

    }
    /**
     * Admin request to add new product is coming as json array
     * @Route("/admin/product/add", methods="POST")
     */
    public function addProduct(Request $request, UserInterface $account = null){
        
        if(!$account){
            throw new UsernameNotFoundException("Admin must log in");
        }
        
        $product = $this->productService->addProduct($request);
        return JsonResponse::fromJsonString($this->serializer->serialize($product,'json'));
    }
}

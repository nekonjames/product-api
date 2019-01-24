<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use App\Entity\Bundle;
use App\Entity\Discount;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Description of ProductService
 *
 * @author Nekon
 */
class ProductService {
    //put your code here
    private $productRepository;
    private $entityManager;
    
    //Product fields
    private $productName, $productDescription,$productPrice,$productType,$productDiscountType,$productDiscount;


    public function __construct(ProductRepository $productRepository, EntityManagerInterface $entityManager) {
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
    }
    
    public function addProduct(Request $request){
        
        $jsonContent = json_decode($request->getContent(),true);
        //Validate Product Parameters
        
        if(isset($jsonContent["name"])){
            $this->productName = $jsonContent["name"];
        }else{
            $data = array('error'=>TRUE,'message' => 'Invalid Product Name');
            return $data;
        }
        
        if(isset($jsonContent["description"])){
            $this->productDescription = $jsonContent["description"];
        }else{
            $this->productDescription = "";
        }
        
        if(isset($jsonContent["price"]) && $jsonContent["price"] > 0){
            $this->productPrice = $jsonContent["price"];
        }else{
            $data = array('error'=>TRUE,'message' => 'Invalid Product Price');
            return $data;
        }
        
        if(isset($jsonContent["discount"]["type"])){
            $this->productDiscountType = $jsonContent["discount"]["type"];
        }else{
            $this->productDiscountType = "";
        }
        if(isset($jsonContent["discount"]["discount"])){
            $this->productDiscount = $jsonContent["discount"]["discount"];
        }else{
            $this->productDiscount = 0;
        }
        
        if(isset($jsonContent["type"]) && $jsonContent["type"] == 'single'){
            $this->productType = "single";
            $product = new Product();                    
        }else if(isset($jsonContent["type"]) && $jsonContent["type"] == 'bundle'){
            $this->productType = "bundle";
            $product = new Bundle();
        }else{
            $data = array('error'=>TRUE,'message' => 'Invalid Product Type. Product type must be single or bundle');
            return $data;
        }
        
        $children = isset($jsonContent["children"]) ? $jsonContent["children"] : [];
        if (count($children) == 0 && $product instanceof Bundle) {
            $data = array('error'=>TRUE,'message' => 'Bundle must have one or more products');
            return $data;
        }
        
        $product->setName($this->productName);
        $product->setDescription($this->productDescription);
        $product->setPrice($this->productPrice);       
                
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        
        foreach ($children as $productId){
            $bundleProduct = $this->productRepository->find($productId);
            $product->addChildren($bundleProduct);
            $this->entityManager->persist($bundleProduct);
        }
        
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        
        $discount = new Discount();
        $discount->setProduct($product);
        $discount->setType($this->productDiscountType);
        $discount->setDiscount($this->productDiscount);
        $this->entityManager->persist($discount);
        $this->entityManager->flush();

        return $product;
    }
}

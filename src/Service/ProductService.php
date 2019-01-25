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
use App\Repository\DiscountRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Exception\RuntimeException;
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
    private $discountRepository;
    private $entityManager;
    
    //Product fields
    private $productName, $productDescription,$productPrice,$productType,$productDiscountType,$productDiscount;


    public function __construct(ProductRepository $productRepository,DiscountRepository $discountRepository, EntityManagerInterface $entityManager) {
        $this->productRepository = $productRepository;
        $this->discountRepository = $discountRepository;
        $this->entityManager = $entityManager;
    }
    
    public function addProduct(Request $request){
        
        $jsonContent = json_decode($request->getContent(),true);
        //Validate Product Parameters
                                
        $this->productName = $jsonContent["name"];
        $this->productDescription = $jsonContent["description"];        
        $this->productPrice = $jsonContent["price"];        
        $this->productDiscountType = $jsonContent["discount"]["type"];
        $this->productDiscount = $jsonContent["discount"]["discount"];
        
        if(isset($jsonContent["type"]) && $jsonContent["type"] == 'single'){
            $this->productType = "single";
            $product = new Product();                    
        }else if(isset($jsonContent["type"]) && $jsonContent["type"] == 'bundle'){
            $this->productType = "bundle";
            $product = new Bundle();
        }else{
            throw new RuntimeException("Invalid Product Type. Product type must be single or bundle");
        }
        
        $children = isset($jsonContent["children"]) ? $jsonContent["children"] : [];
        if (count($children) == 0 && $product instanceof Bundle) {
            throw new RuntimeException("Bundle must have one or more products");
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
    
    public function updateProduct(Request $request){
        
        $jsonContent = json_decode($request->getContent(),true);
        //Validate Product Parameters
        
        $product = $this->productRepository->find($jsonContent["id"]);
        if(!$product){
            throw new RuntimeException("Invalid Product");
        }
        
        $product->setName($jsonContent["name"]);
        $product->setDescription($jsonContent["description"]);
        $product->setPrice($jsonContent["price"]);            
                
        if(isset($jsonContent["type"]) && $jsonContent["type"] == 'single'){
            $this->productType = "single";         
        }else if(isset($jsonContent["type"]) && $jsonContent["type"] == 'bundle'){
            $this->productType = "bundle";
            $product = new Bundle();
        }else{
            throw new RuntimeException("Invalid Product Type. Product type must be single or bundle");
        }
        
        $children = isset($jsonContent["children"]) ? $jsonContent["children"] : [];
        if (count($children) == 0 && $product instanceof Bundle) {
            throw new RuntimeException("Bundle must have one or more products");
        }      
                            
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        
        foreach ($children as $productId){
            $bundleProduct = $this->productRepository->find($productId);
            $product->addChildren($bundleProduct);
            $this->entityManager->persist($bundleProduct);
        }
        
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        
        $discount = $this->discountRepository->findOneBy(["product"=>$product]);
        if(!$discount){
            $discount = new Discount();
        }
        $discount->setProduct($product);
        $discount->setType($jsonContent["discount"]["type"]);
        $discount->setDiscount($jsonContent["discount"]["discount"]);
        
                
        $this->entityManager->persist($discount);
        $this->entityManager->flush();

        return $product;
    }
    
    public function deleteProduct($productId){
        
        $product = $this->productRepository->find($productId);
        if(!$product){
            throw new RuntimeException("Invalid Product");
        }
        
        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return $product;
    }
    
    public function getAllProducts(){
        $product = $this->productRepository->findAll();
        return $product;
    }
}

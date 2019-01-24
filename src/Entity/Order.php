<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="orders")
 *
 * @author Nekon
 */
class Order {
    //put your code here
    
    public function __construct() {        
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->products = new ArrayCollection();
    }
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var float
     * @ORM\Column(type="decimal", scale=2, nullable=false)
     */
    private $price;
    
    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="orders")
     */
    private $customerAccount;
    
    /**
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="orders")
     * @ORM\JoinTable(name="product_order")
     */
    private $products;
    
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
    
    public function getId() {
        return $this->id;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getCustomerAccount() {
        return $this->customerAccount;
    }

    public function getProducts() {
        return $this->products;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setCustomerAccount(Account $customerAccount) {
        $this->customerAccount = $customerAccount;
    }

    public function setProducts(ArrayCollection $products) {
        $this->products = $products;
    }
    
    public function addProduct(Product $product){
        $this->products->add($product);
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function setCreatedAt(\DateTime $createdAt) {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt) {
        $this->updatedAt = $updatedAt;
    }



}

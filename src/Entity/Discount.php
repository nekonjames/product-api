<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Discount
 * @ORM\Entity
 * @ORM\Table(name="discount")
 * @author Nekon
 */
class Discount {
    //put your code here
    public function __construct() {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();        
    }
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var integer
     * @ORM\Column(type="integer", scale=2, nullable=true)
     */
    private $discount;
    
    /**
     * @var Product
     * @ORM\OneToOne(targetEntity="Product", inversedBy="discount")
     * @ORM\JoinColumn(nullable=true)
     */
    private $product;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $type;
    
    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
    
    public function getId() {
        return $this->id;
    }

    public function getDiscount() {
        return $this->discount;
    }

    public function getProduct() {
        return $this->product;
    }

    public function getType() {
        return $this->type;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setDiscount($discount) {
        $this->discount = $discount;
    }

    public function setProduct($product) {
        $this->product = $product;
    }

    public function setType($type) {
        $this->type = $type;
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

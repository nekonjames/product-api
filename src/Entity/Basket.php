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
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Description of Basket
 * @ORM\Entity
 * @ORM\Table(name="basket")
 * @author Nekon
 */
class Basket {
    //put your code here
    
    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->products = new ArrayCollection();
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @var Product|null
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="baskets")
     *
     */
    private $products;
    
    /**
     * @var Account
     * @ORM\OneToOne(targetEntity="Account", inversedBy="baskets")
     * @ORM\JoinColumn(nullable=true)
     */
    private $customerAccount;
    
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
    
    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }    
    public function getUser()
    {
        return $this->user;
    }
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
        return $this;
    }
    
    public function getProducts()
    {
        return $this->products;
    }
    public function setProducts(ArrayCollection $products)
    {
        $this->products = $products;
        return $this;
    }
    public function addProduct(Product $product)
    {
        $this->products->add($product);
    }
    
    public function getCustomerAccount() {
        return $this->customerAccount;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function setCustomerAccount($customerAccount) {
        $this->customerAccount = $customerAccount;
    }

    public function setCreatedAt(DateTime $createdAt) {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(DateTime $updatedAt) {
        $this->updatedAt = $updatedAt;
    }

     /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("totalPrice")
     */
    public function finalPrice()
    {
        $finalPrice = 0;
        foreach ($this->getProducts() as $p) {
            /** @var Product $p */
            $finalPrice += $p->getFinalPrice();
        }
        return $finalPrice;
    }
}

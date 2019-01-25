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
use Symfony\Component\Validator\Constraint as Assert;

/**
 * Description of Products
 *
 * @author Nekon
 * 
 * @ORM\Entity
 * @ORM\Table(name="product")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"single" = "Product", "bundle" = "Bundle"})
 */

class Product {
    //put your code here
    
    public function __construct() {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->orders = new ArrayCollection();
        $this->bundles = new ArrayCollection();
        $this->baskets = new ArrayCollection();
        $this->discount = new Discount();
    }
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")     
     */
    private $id;
    
    /**
     * @var string
     * @Assert\NotBlank(message="Product Name is required")
     * @ORM\Column(type="string", length=100)
     * @Serializer\Type("string")
     */
    private $name;
    
    /**
     * @var string
     * @Assert\NotBlank(message="Product description is required")
     * @Assert\Length(
     *      max=200,
     *      maxMessage="Description cannot be more than {{ limit }} characters"
     * )
     * @ORM\Column(type="string")
     * @Serializer\Type("string")
     */
    private $description;
        
    /**
     * @var float
     * @Assert\NotBlank(message="Price is required")
     * @ORM\Column(type="decimal", scale=2, nullable=false)
     * @Serializer\Type("float")
     */
    private $price;
    
    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Order", mappedBy="products")   
     * @Serializer\Exclude() 
     *  
     */
    private $orders;

    /**
     * @var Discount
     * @ORM\OneToOne(targetEntity="Discount", mappedBy="product", cascade={"persist", "remove"})
     */
    private $discount;
    
    /**
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="children")
     * @Serializer\Exclude()
     */
    private $bundles;
    
    /**
     * @var ArrayCollection|null
     * @ORM\ManyToMany(targetEntity="Basket",inversedBy="products")
     * @ORM\JoinTable(name="product_basket")
     * @Serializer\Exclude()
     *
     */
    private $baskets;
    
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


    public function getBundles() {
        return $this->bundles;
    }

    public function setBundles(ArrayCollection $bundles) {
        $this->bundles = $bundles;
    }
        
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setPrice($price) {
        $this->price = $price;
    }
    
    public function getOrders() {
        return $this->orders;
    }

    public function setOrders(ArrayCollection $orders) {
        $this->orders = $orders;
    }
    public function addOrder(Order $order) {
        $this->orders->add($order);
    }

    public function getDiscount() {
        return $this->discount;
    }

    public function setDiscount(Discount $discount) {
        $this->discount = $discount;
    }
        
    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function setCreatedAt(DateTime $createdAt) {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(DateTime $updatedAt) {
        $this->updatedAt = $updatedAt;
    }

    public function getBaskets() {
        return $this->baskets;
    }

    public function setBaskets(ArrayCollection $baskets) {
        $this->baskets = $baskets;
    }
    
    public function addBasket(Basket $baskets) {
        $this->baskets->add($baskets);
    }

        
    public function getFinalPrice()
    {
        if ($this->getDiscount()) {
            
            if ($this->getDiscount()->getType() == "percentage") {
                $finalPrice = (float)$this->getPrice() - ($this->getPrice() * $this->getDiscount()->getDiscount()) / 100;
            }else{
                $finalPrice = $this->getPrice() - $this->getDiscount()->getDiscount();
            }
            
            return (float)number_format($finalPrice, 2);
        }
        return $this->getPrice();
    }

    
}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Description of Account
 * @ORM\Entity
 * @ORM\Table(name="account")
 * 
 * @ORM\AttributeOverrides({
 *      @ORM\AttributeOverride(name="username",
 *          column=@ORM\Column(
 *              name     = "username",
 *              length   = 191,
 *              unique   = true
 *          )
 *      ),
 *      @ORM\AttributeOverride(name="accessToken",
 *          column=@ORM\Column(
 *              name     = "access_token",
 *              length   = 191,
 *              unique   = true
 *          )
 *      )
 * })
 * @author Nekon
 */
class Account implements UserInterface{
    
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
     * @ORM\Column(type="string", unique=true)
     */
    private $username;
    
    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $accessToken;
    
    /**
     * @ORM\Column(type="string")
     */
    private $accountType;
    
    /**
     * @ORM\OneToOne(targetEntity="Basket", mappedBy="customerAccount", cascade={"persist", "remove"})
     */
    private $baskets;
    
    /**
     * @ORM\OneToMany(targetEntity="Order" ,mappedBy="customerAccount")
     */
    private $orders;
    
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

    public function getUsername() {
        return $this->username;
    }

    public function getAccessToken() {
        return $this->accessToken;
    }

    public function getAccountType() {
        return $this->accountType;
    }

    public function getOrders() {
        return $this->orders;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setAccessToken($accessToken) {
        $this->accessToken = $accessToken;
    }

    public function setAccountType($accountType) {
        $this->accountType = $accountType;
    }

    public function setOrders($orders) {
        $this->orders = $orders;
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
    
    public function getBaskets() {
        return $this->baskets;
    }

    public function setBaskets($baskets) {
        $this->baskets = $baskets;
    }
    
    public function eraseCredentials() {
        
    }

    public function getPassword() {
        
    }

    public function getRoles() {
        //Check to assign role accordingly. We only have two types of account, customer and admin
        if($this->getAccountType() == "customer"){
            $roles = ['ROLE_USER'];
        }else if($this->getAccountType() == "admin"){
            $roles = ['ROLE_ADMIN'];
        }else{
            $roles = [];
        }        

        return $roles;
    }

    public function getSalt() {
        
    }
        

}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * Description of Bundle
 * @ORM\Entity
 * @author Nekon
 */
class Bundle extends Product{
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->children = new ArrayCollection();
    }
    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="bundles")
     * @ORM\JoinTable(name="bundles",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="bundle_id", referencedColumnName="id")}
     *      )
     */
    private $children;
    
    public function getChildren() {
        return $this->children;
    }

    public function setChildren(ArrayCollection $children) {
        $this->children = $children;
    }

    public function addChildren(Product $product){
        $this->children->add($product);
    }

}

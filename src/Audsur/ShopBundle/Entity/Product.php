<?php
namespace Audsur\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, unique=false)
     */
    protected $slug;

    /**
     * @ORM\Column(type="decimal", scale=2, precision=6, nullable=true)
     */
    protected $priceEx;

    /**
     * @ORM\Column(type="decimal", scale=2, precision=6, nullable=true)
     */
    protected $priceIn;

    /**
     * @ORM\Column(type="decimal", nullable=false)
     */
    protected $stock;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="Brand", inversedBy="products")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    protected $brand;

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="product")
     */
    protected $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Product
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set priceEx
     *
     * @param string $priceEx
     * @return Product
     */
    public function setPriceEx($priceEx)
    {
        $this->priceEx = $priceEx;

        return $this;
    }

    /**
     * Get priceEx
     *
     * @return string 
     */
    public function getPriceEx()
    {
        return $this->priceEx;
    }

    /**
     * Set priceIn
     *
     * @param string $priceIn
     * @return Product
     */
    public function setPriceIn($priceIn)
    {
        $this->priceIn = $priceIn;

        return $this;
    }

    /**
     * Get priceIn
     *
     * @return string 
     */
    public function getPriceIn()
    {
        return $this->priceIn;
    }

    /**
     * Set stock
     *
     * @param string $stock
     * @return Product
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return string 
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set category
     *
     * @param \Audsur\ShopBundle\Entity\Category $category
     * @return Product
     */
    public function setCategory(\Audsur\ShopBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Audsur\ShopBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set brand
     *
     * @param \Audsur\ShopBundle\Entity\Brand $brand
     * @return Product
     */
    public function setBrand(\Audsur\ShopBundle\Entity\Brand $brand = null)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return \Audsur\ShopBundle\Entity\Brand 
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Add image
     *
     * @param \Audsur\ShopBundle\Entity\Image $image
     * @return Product
     */
    public function addImage(\Audsur\ShopBundle\Entity\Image $image)
    {
        $this->image[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \Audsur\ShopBundle\Entity\Image $image
     */
    public function removeImage(\Audsur\ShopBundle\Entity\Image $image)
    {
        $this->image->removeElement($image);
    }

    /**
     * Get image
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }
}

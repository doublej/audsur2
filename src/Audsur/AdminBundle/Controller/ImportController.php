<?php

namespace Audsur\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Audsur\ShopBundle\Entity\Category;
use Audsur\ShopBundle\Entity\Brand;
use Audsur\ShopBundle\Entity\Product;
use Cocur\Slugify\Slugify;

class ImportController extends Controller
{
    public function uploadAction()
    {
        return $this->render('AudsurAdminBundle:Import:upload.html.twig', array(
                // ...
            ));    }

    public function importAction()
    {

        if ($_FILES) {

            $slugify            = new Slugify();
            $file               = $_FILES['csv']['tmp_name'];
            $handle             = fopen($file,"r");
            $amountProducts     = 0;
            $amountCategories   = 0;
            $amountBrands       = 0;

            if (($data = fgetcsv($handle, 1000, ',')) !== FALSE)
            {
                $keys = $data;
            }

            // Get the rest
            while ($column = fgetcsv($handle, 1000, ',')){

                $amountProducts++;

                $column = array_combine($keys, $column);

                $product = new Product();
                $product
                    ->setName($column['type'])
                    ->setPriceIn($column['priceIn'])
                    ->setStock($column['stock'])
                    ->setDescription($column['product_description'])
                    ->setSlug($slugify->slugify($column['brand'].'-'.$column['type']));

                // check if this category exists
                $category = $this
                    ->getDoctrine()
                    ->getRepository('AudsurShopBundle:Category')
                    ->findOneBy(array('name' => $column['category']));

                if(!$category){
                    $category = new Category();
                    $category
                        ->setName($column['category'])
                        ->setSlug($slugify->slugify($column['category']))
                        ->setDescription('descr');
                    $amountCategories++;
                }
                $product->setCategory($category);

                // check if this brand exists
                $brand = $this->getDoctrine()
                    ->getRepository('AudsurShopBundle:Brand')
                    ->findOneBy(array('name' => $column['brand']));

                if(!$brand){
                    $brand = new Brand();
                    $brand
                        ->setName($column['brand'])
                        ->setSlug($slugify->slugify($column['brand']))
                        ->setDescription('descr');
                    $amountBrands++;
                }
                $product->setBrand($brand);

                $em = $this->getDoctrine()->getManager();
                $em->persist($category);
                $em->persist($brand);
                $em->persist($product);
                $em->flush();
            }
        }
        return $this->render('AudsurAdminBundle:Import:import.html.twig', array(
                'status' => 'success',
                'amountProducts' => $amountProducts,
                'amountCategories' => $amountCategories,
                'amountBrands' => $amountBrands,
            ));

    }

}


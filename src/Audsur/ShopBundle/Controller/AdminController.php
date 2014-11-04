<?php

namespace Audsur\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Audsur\ShopBundle\Entity\Category;
use Audsur\ShopBundle\Entity\Brand;
use Audsur\ShopBundle\Entity\Product;

class AdminController extends Controller
{

    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }

    public function createProductAction()
    {
        /* $existingCategory = $this->getDoctrine()
             ->getRepository('AudsurShopBundle:Category')
             ->findOneBy(array('name' => 'Tape Recorders'));

         if(!$existingCategory){

             $category = new Category();
             $category->setName('Tape Recorders');
             $category->setDescription('bla');

         }
         die;*/

        $category = $this->getDoctrine()
            ->getRepository('AudsurShopBundle:Category')
            ->findOneBy(array('name' => 'Radio'));

        $brand = new Brand();
        $brand->setName('AKAIa');
        $brand->setDescription('bla');

        $product = new Product();
        $product->setName('AC400');
        $product->setPriceIn(19.99);
        $product->setStock(1);
        $product->setDescription('bla');


        // relate this product to the category
        $product->setCategory($category);
        $product->setBrand($brand);

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->persist($brand);
        $em->persist($product);
        $em->flush();

        return new Response(
            'Created product id: '.$product->getId()
            .' and category id: '.$category->getId()
            .' and brand id: '.$brand->getId()
        );
    }
    public function uploadAction()
    {

        if ($_FILES) {

            $file       = $_FILES['csv']['tmp_name'];
            $handle     = fopen($file,"r");
            $message = 'Running CSV file...<br>';
            $i = 0;

            if (($data = fgetcsv($handle, 1000, ',')) !== FALSE)
            {
                $keys = $data;
            }

            // Get the rest
            while ($column = fgetcsv($handle, 1000, ',')){


                $message = '<br><br>Record #'.$i.'<br>';
                $i++;

                $column = array_combine($keys, $column);

                $product = new Product();
                $product->setName($column['type']);
                $product->setPriceIn($column['priceIn']);
                $product->setStock($column['stock']);
                $product->setDescription($column['product_description']);

                $productSlug = $this->slugify($column['brand'].'-'.$column['type']);
                $product->setSlug($productSlug);

                // check if this category exists
                $category = $this->getDoctrine()
                    ->getRepository('AudsurShopBundle:Category')
                    ->findOneBy(array('name' => $column['category']));

                $message = $message.'<br> - '.$column['category'];

                if(!$category){

                    $category = new Category();
                    $category->setName($column['category']);
                    $category->setSlug($this->slugify($column['category']));
                    $category->setDescription('descr');

                    // relate this product
                    $product->setCategory($category);

                    $message = $message.' is new';

                }else{
                    $product->setCategory($category);

                    $message = $message.' is old';
                }

                $message = $message.'<br> - '.$column['brand'];


                // check if this brand exists
                $brand = $this->getDoctrine()
                    ->getRepository('AudsurShopBundle:Brand')
                    ->findOneBy(array('name' => $column['brand']));

                if(!$brand){

                    $brand = new Brand();
                    $brand->setName($column['brand']);
                    $brand->setSlug($this->slugify($column['brand']));
                    $brand->setDescription('descr');

                    // relate this product
                    $product->setBrand($brand);

                    $message = $message.' is new';

                }else{
                    $product->setBrand($brand);

                    $message = $message.' is old';

                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($category);
                $em->persist($brand);
                $em->persist($product);
                $em->flush();

                echo $message;

            }

            die;
        }else{
            return $this->render('AudsurShopBundle:Default:upload.html.twig');
        }
    }
}
<?php

namespace Audsur\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Audsur\ShopBundle\Entity\Category;
use Audsur\ShopBundle\Entity\Brand;
use Audsur\ShopBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{


    public function getProductsAction($paginatorIndex)
    {
        $products = $this->getDoctrine()
            ->getRepository('AudsurShopBundle:Product')
            ->findAll();

        return $this->render('AudsurShopBundle:Default:products.html.twig', array(
                'products' => $products,
                'paginatorIndex' => $paginatorIndex,
            )
        );
    }

    public function getBrandAction()
    {

        /*
         * @todo vind alle producten van een categorie. dit is stuk.
         */
        $products = $this->getDoctrine()
            ->getRepository('AudsurShopBundle:Product')
            ->findByBrand(1);

        print_r($products);

        $paginatorIndex = 1;

        return $this->render('AudsurShopBundle:Default:products.html.twig', array(
                'products' => $products,
                'paginatorIndex' => $paginatorIndex
            )
        );

    }

}
<?php

namespace Audsur\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller
{
    public function categoryListAction()
    {
        $categories = $this->getDoctrine()
            ->getRepository('AudsurShopBundle:Category')
            ->findAll();

        $brands = $this->getDoctrine()
            ->getRepository('AudsurShopBundle:Brand')
            ->findAll();


        return $this->render('AudsurShopBundle:Default:categoryList.html.twig', array(
                'categories' => $categories,
                'brands' => $brands
            ));
    }

    public function brandListAction()
    {
        $categories = $this->getDoctrine()
            ->getRepository('AudsurShopBundle:Category')
            ->findAll();

        $brands = $this->getDoctrine()
            ->getRepository('AudsurShopBundle:Brand')
            ->findAll();


        return $this->render('AudsurShopBundle:Default:brandList.html.twig', array(
                'categories' => $categories,
                'brands' => $brands
            ));
    }
}

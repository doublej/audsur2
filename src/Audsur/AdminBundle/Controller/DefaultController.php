<?php

namespace Audsur\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Audsur\ShopBundle\Entity\Category;
use Audsur\ShopBundle\Entity\Brand;
use Audsur\ShopBundle\Entity\Product;
use Cocur\Slugify\Slugify;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AudsurAdminBundle:Default:index.html.twig', array('name'=>'bla'));
    }
    public function productOverviewAction()
    {
        $products
        return $this->render('AudsurAdminBundle:Default:overview.html.twig', array('name'=>'bla'));
    }

}
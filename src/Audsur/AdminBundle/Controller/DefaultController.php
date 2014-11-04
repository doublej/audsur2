<?php

namespace Audsur\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Audsur\ShopBundle\Entity\Category;
use Audsur\ShopBundle\Entity\Brand;
use Audsur\ShopBundle\Entity\Product;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AudsurAdminBundle:Default:index.html.twig', array('name'=>'bla'));
    }

    public function productOverviewAction($paginatorIndex)
    {
        $products = $this->getDoctrine()
            ->getRepository('AudsurShopBundle:Product')
            ->findAll();

        return $this->render('AudsurAdminBundle:Default:overview.html.twig', array(
                'paginatorIndex'    => $paginatorIndex,
                'products'          => $products,
            )
        );
    }

    public function productEditAction($id)
    {
        $product = $this->getDoctrine()
            ->getRepository('AudsurShopBundle:Product')
            ->find($id);

        $categories = $this->getDoctrine()
            ->getRepository('AudsurShopBundle:Category')
            ->findAll();

        $brands = $this->getDoctrine()
            ->getRepository('AudsurShopBundle:Brand')
            ->findAll();

        return $this->render('AudsurAdminBundle:Default:edit.html.twig', array(
                'categories'    => $categories,
                'brands'        => $brands,
                'product'       => $product,
            )
        );
    }

    public function productEdit2Action($id)
    {
        $product = $this->getDoctrine()
            ->getRepository('AudsurShopBundle:Product')
            ->find($id);

        $categories = $this->getDoctrine()
            ->getRepository('AudsurShopBundle:Category')
            ->findAll();

        $brands = $this->getDoctrine()
            ->getRepository('AudsurShopBundle:Brand')
            ->findAll();


        // just setup a fresh $task object (remove the dummy data)
        $task = new Task();

        $form = $this->createFormBuilder($task)
            ->add('task', 'text')
            ->add('dueDate', 'date')
            ->add('save', 'submit', array('label' => 'Create Task'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database

            return $this->redirect($this->generateUrl('task_success'));
        }

        // ...
    }

    public function productSaveAction()
    {
        print_r($_POST);
        die;
        return new Response('yo');
    }

}
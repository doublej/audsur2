<?php

namespace Audsur\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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

    public function productEditAction($id, $type, Request $request)
    {

        if($type == 'add'){
            $product = new Product();
        }else{
            $product = $this->getDoctrine()
                ->getRepository('AudsurShopBundle:Product')
                ->find($id);
        }

        $productForm = $this->createFormBuilder($product)
            ->add('category', 'entity', array(
                    'class' => 'AudsurShopBundle:Category',
                    'multiple'  => false,
                    'property' => 'name',
                ))
            ->add('brand', 'entity', array(
                    'class' => 'AudsurShopBundle:Brand',
                    'multiple'  => false,
                    'property' => 'name',
                ))
            ->add('name', 'text')
            ->add('stock', 'number')
            ->add('priceIn', 'money')
            ->add('priceEx', 'money')
            ->add('description', 'textarea')
            ->add('save', 'submit', array('label' => 'Opslaan'))
            ->add('saveAndAdd', 'submit', array('label' => 'Opslaan en nog een product toevoegen'))
            ->getForm();

        $productForm->handleRequest($request);

        if ($productForm->isValid()) {

            $slugify = new Slugify();
            $formdata = $productForm->getData();

            $product->setSlug($slugify->slugify($formdata->getBrand()->getName().'-'.$formdata->getName()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            if( $productForm->get('saveAndAdd')->isClicked() ){
                return $this->redirect($this->generateUrl('admin_product_add_success'));
            }else{
                return $this->redirect($this->generateUrl('admin_product_overview'));
            }
        }

        return $this->render('AudsurAdminBundle:Default:new.html.twig', array(
            'productForm' => $productForm->createView()
        ));

    }

    public function productSaveSuccess()
    {

    }

    public function productSaveAction()
    {

        return new Response('yo');
    }

}
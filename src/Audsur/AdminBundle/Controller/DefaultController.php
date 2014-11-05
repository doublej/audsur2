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

    public function productOverviewAction($paginatorIndex, Request $request)
    {

        $productsRepo = $this->getDoctrine()->getRepository('AudsurShopBundle:Product');
        $em = $this->getDoctrine()->getManager();

        $filterForm = $this->createFormBuilder()
            ->add('category', 'entity', array(
                    'class' => 'AudsurShopBundle:Category',
                    'multiple'  => false,
                    'required' => false,
                    'property' => 'name',
                    'empty_value'   => 'Alle',
                    'data' => '',//$em->getReference("AudsurShopBundle:Category", 3),
                ))
            ->add('brand', 'entity', array(
                    'class' => 'AudsurShopBundle:Brand',
                    'multiple'  => false,
                    'required' => false,
                    'property' => 'name',
                    'empty_value'   => 'Alle',
                    'data' => '',//$em->getReference("AudsurShopBundle:Brand", 74),
                ))
            ->add('limit', 'choice', array(
                    'choices'   => array(
                        10 => 10,
                        100 => 100,
                    ),
                    'label' => 'Resultaten per pagina',
                    'multiple'  => false,
                ))
            ->add('save', 'submit', array('label' => 'Filter'))
            ->getForm();

        $filterForm->handleRequest($request);

        $products = $productsRepo->findAll();
        $limit = 10;

        if ($filterForm->isValid()) {
            $limit = $filterForm->get('limit')->getData();
            $catData = $filterForm->get('category')->getData();
            $braData = $filterForm->get('brand')->getData();

            if($catData && $braData ){
                $products = $productsRepo->findBy(array(
                        'brand' => $braData->getId(),
                        'category' => $catData->getId(),
                    ));
            }elseif($braData){
                $products = $productsRepo->findByBrand($braData->getId());
            }elseif($catData){
                $products = $productsRepo->findByCategory($catData->getId());
            }else{
            }


        }

        return $this->render('AudsurAdminBundle:Default:overview.html.twig', array(
                'paginatorIndex' => $paginatorIndex,
                'products' => $products,
                'filterForm' => $filterForm->createView(),
                'limit' => $limit,
            )
        );
    }

    public function productEditAction($id, $type, Request $request)
    {

        if($type == 'add'){
            $product = new Product();
            $flashMessage = 'Product is succesvol toegevoegd.';
        }else{
            $product = $this->getDoctrine()
                ->getRepository('AudsurShopBundle:Product')
                ->find($id);
            $flashMessage = 'Product is succesvol gewijzigd.';
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

            $this->get('session')->getFlashBag()->add( 'notice', $flashMessage );

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

    public function productDeleteAction($id)
    {

        $product = $this->getDoctrine()
            ->getRepository('AudsurShopBundle:Product')
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        $this->get('session')->getFlashBag()->add( 'notice', 'Product is succesvol verwijderd' );

        return $this->redirect($this->generateUrl('admin_product_overview'));

    }

}
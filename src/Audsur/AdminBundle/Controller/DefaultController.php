<?php

namespace Audsur\AdminBundle\Controller;

use Cocur\Slugify\Slugify;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Audsur\ShopBundle\Entity\Product;
use Audsur\ShopBundle\Entity\Image;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AudsurAdminBundle:Default:index.html.twig', array('name'=>'bla'));
    }

    public function productOverviewAction(
        Request $request,
        $paginatorIndex = 0,
        $predifinedCategory = null,
        $predefinedBrand = null
//      $predifinedLimit = null
    )
    {
        $productsRepo = $this->getDoctrine()->getRepository('AudsurShopBundle:Product');
        $query = $productsRepo->createQueryBuilder('p');

//        if(!$predifinedLimit){
//            $limitResult = 10;
//        }
        if($predifinedCategory){

            $query = $query->andWhere('p.category = '.$predifinedCategory);

            $predifinedCategory = $this
                ->getDoctrine()
                ->getManager()
                ->getReference("AudsurShopBundle:Category", $predifinedCategory);
        }
        if($predefinedBrand){

            $query = $query->andWhere('p.brand = '.$predefinedBrand);

            $predefinedBrand = $this
                ->getDoctrine()
                ->getManager()
                ->getReference("AudsurShopBundle:Brand", $predefinedBrand);

        }

        $filterForm = $this->createFormBuilder()
            ->add('category', 'entity', array(
                    'class' => 'AudsurShopBundle:Category',
                    'multiple'  => false,
                    'required' => false,
                    'property' => 'name',
                    'empty_value'   => 'Alle',
                    'data' => $predifinedCategory,
                ))
            ->add('brand', 'entity', array(
                    'class' => 'AudsurShopBundle:Brand',
                    'multiple'  => false,
                    'required' => false,
                    'property' => 'name',
                    'empty_value'   => 'Alle',
                    'data' => $predefinedBrand,
                ))
// >> CANT GET THIS TO WORK
//            ->add('limit', 'choice', array(
//                    'choices'   => array(
//                        '10' => 10,
//                        '100' => 100,
//                        '1000' => 1000,
//                    ),
//                    'required' => false,
//                    'label' => 'Resultaten per pagina',
//                    'multiple'  => false,
//                    'empty_value'   => 'Ongelimiteerd',
//                    'data' => $limitResult,
//                ))
            ->add('save', 'submit', array('label' => 'Filter'))
            ->getForm();

        $filterForm->handleRequest($request);

//        if($filterForm->get('limit')->getData()){
//            $limitResult = $filterForm->get('limit')->getData();
//        }

        if($filterForm->isValid() && !$predefinedBrand && !$predifinedCategory){

            if($filterForm->get('brand')->getData()){
                $query = $query->andWhere('p.brand = '.$filterForm->get('brand')->getData()->getId());
            }
            if($filterForm->get('category')->getData()){
                $query = $query->andWhere('p.category = '.$filterForm->get('category')->getData()->getId());
            }

        }

        $products = $query->getQuery()->getResult();

        $totalResult = count($products);

        return $this->render('AudsurAdminBundle:Default:overview.html.twig', array(
                'paginatorIndex' => $paginatorIndex,
                'products' => $products,
                'filterForm' => $filterForm->createView(),
                'limitResult' => 9999,
//                'totalResult' => $totalResult,
                'request' => $request,
            )
        );
    }

    public function productEditAction($id, $type, Request $request)
    {
        /*
         * @todo Split this into more readable functions
         */

        if($type == 'add'){
            $product = new Product();
            $flashMessage = 'Product is succesvol toegevoegd.';
        }else{
            $product = $this->getDoctrine()
                ->getRepository('AudsurShopBundle:Product')
                ->find($id);
            $flashMessage = 'Product is succesvol gewijzigd.';
        }

        $productForm = $this->get('form.factory')->createNamedBuilder('productform', 'form', $product)
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


        if($type !== 'add'){

            $image = new Image();

            $imageForm = $this->get('form.factory')->createNamedBuilder('imageform', 'form', $image)
                ->add('product', 'entity', array(
                        'class' => 'AudsurShopBundle:Product',
                        'multiple'  => false,
                        'property' => 'name',
                        'data' => $product,
                        'disabled' => false,
                    ))
                /*
                 * @todo change the product dropdown into a hidden field, needs workaround
                 * http://lrotherfield.com/blog/symfony2-forms-hidden-entity-type-part-2/
                 */
                ->add('name')
                ->add('file')
                ->add('save', 'submit', array('label' => 'Opslaan'))
                ->getForm();

            $imageForm->handleRequest($request);

            $imageFormView = $imageForm->createView();

        }else{
            $imageFormView = '';
        }


        if ($request->request->has('productform')) {

            $this->get('session')->getFlashBag()->add( 'notice', $flashMessage );

            $slugify = new Slugify();
            $formdata = $productForm->getData();

            $product->setSlug($slugify->slugify($formdata->getBrand()->getName().'-'.$formdata->getName()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            if($productForm->get('saveAndAdd')->isClicked()){
                return $this->redirect($this->generateUrl('admin_product_add'));
            }else{
                return $this->redirect($this->generateUrl('admin_product_overview'));
            }

        }

        if ($request->request->has('imageform')) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();

        }
        if ($productForm->isValid()) {

        }

        return $this->render('AudsurAdminBundle:Default:new.html.twig', array(
                'productForm' => $productForm->createView(),
                'images' => $product->getImages(),
                'imageForm' => $imageFormView,
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

        $this->get('session')->getFlashBag()->add('notice', 'Product is succesvol verwijderd');

        return $this->redirect($this->generateUrl('admin_product_overview'));

    }

    public function imageDeleteAction($productId, $imageId){

        $product = $this->getDoctrine()
            ->getRepository('AudsurShopBundle:Image')
            ->find($imageId);

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Image is succesvol verwijderd');

        return $this->redirect($this->generateUrl('admin_product_edit', array('id' => $productId)));

    }

}
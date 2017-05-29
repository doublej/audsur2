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


    public function getAllProductsAction($paginatorIndex = 0)
    {
        $products = $this->getDoctrine()
            ->getRepository('AudsurShopBundle:Product')
            ->findAll();

        return $this->render('AudsurShopBundle:Default:productOverview.html.twig', array(
                'products' => $products,
                'paginatorIndex' => $paginatorIndex,
            )
        );
    }

    /*
     * @todo create links based on slugs, which are currently not unique
     */
    public function getSingleProductAction($productId)
    {
        $product = $this->getDoctrine()
            ->getRepository('AudsurShopBundle:Product')
            ->findOneById($productId);

        return $this->render('AudsurShopBundle:Default:singleProduct.html.twig', array(
                'product' => $product
            )
        );
    }

    /*
     * @todo refactor, looks too complex
     */
//    public function getCategoryAction($slug, $type = null, $grouped = true)
//    {
//
//        $selectedGroup = $this->getDoctrine()->getRepository(
//            'AudsurShopBundle:'.$type
//        );
//        $selectedGroup = $selectedGroup->findOneBySlug($slug);
//
//        $products = $this->getDoctrine()
//            ->getRepository('AudsurShopBundle:Product');
//
//        $query = $products->createQueryBuilder('p')
//            ->join('p.brand','b')
//            ->join('p.category','g')
//            ->select('p', 'b', 'g')
//            ->where('p.'.strtolower($type).' = '.$selectedGroup->getId());
//
//        $products = $query->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
//
//
//        $treeArray = array();
//        foreach($products as $el) {
//            $firstLetter = strtoupper(substr($el['brand']['name'], 0, 1));
//            if(!(isset($treeArray[$firstLetter])) ) {
//                $treeArray[$firstLetter] = array();
//
//            }
//            array_push($treeArray[$firstLetter], $el);
//        }
//
//        $paginatorIndex = 1;
//        return $this->render('AudsurShopBundle:Default:productOverview.html.twig', array(
//                'products' => $treeArray,
//                'paginatorIndex' => $paginatorIndex
//            )
//        );
//
//    }

    public function getGroupAction($slug, $type = null, $grouped = true)
    {

        $group = $this->getDoctrine()
            ->getRepository('AudsurShopBundle:'.$type)
            ->findOneByName($slug);
//            ->getProducts();


        echo $slug;
        \Doctrine\Common\Util\Debug::dump($group, 3);
        die;


        return $this->render('AudsurShopBundle:Default:productOverview.html.twig', array(
                'group' => $group
            )
        );

    }

}



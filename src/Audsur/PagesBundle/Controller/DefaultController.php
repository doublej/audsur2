<?php

namespace Audsur\PagesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($page = 'home')
    {
        if ($page == 'home' ){
            $title = 'homeTitle';
            $body = 'homeBody';
        }
        return $this->render('AudsurPagesBundle:Default:index.html.twig', array(
                'title' => $title,
                'body' => $body,
            ));
    }
}

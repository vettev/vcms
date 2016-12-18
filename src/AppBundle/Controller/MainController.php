<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;

class MainController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $configFile = $this->get('kernel')->getRootDir() . '/config/parameters.yml';

        $parameters = Yaml::parse(file_get_contents($configFile))['parameters'];
        if(!(isset($parameters["settings_flag"]) && $parameters['settings_flag']))
            return $this->redirectToRoute('admin_creator');

        return $this->render('main/index.html.twig');
    }

    /**
     * @Route("/blog/{page}", name="blog", requirements={"id": "\d+"})
     */
    public function blogAction(Request $request, $page = 1, $home = false)
    {
        $limit = 4;
        $repo = $this->getDoctrine()->getRepository('AppBundle:Post');        
        $query = $repo->getPosts();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, $limit);

        if($home === true)
            return $this->render('templates/clean_posts.html.twig', ['pagination' => $pagination]);
        else
          return $this->render('main/blog.html.twig', ['pagination' => $pagination]);
    }
}

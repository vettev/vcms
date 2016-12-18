<?php 
// src/AppBundle/Menu/MenuBuilder.php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    private $factory;
    private $elements;
    private $auth;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory, $container, $em)
    {
        $this->elements = $em->getRepository('AppBundle:Menu')->findAll();
        $this->auth = $container->get('security.authorization_checker');
        $this->factory = $factory;
    }

    public function createMainMenu(array $options)
    {

        $menu = $this->factory->createItem('root');

        foreach ($this->elements as $link) {
            $name = $link->getName();
            if($link->getRoute())
                $route = $link->getRoute()->getRoute();
            else
                $route = null;
            $displayFor = $link->getDisplayFor();
            $customUrl = $link->getCustomUrl();
            switch($displayFor)
            {
                case 'user':
                    if($this->auth->isGranted('ROLE_USER'))
                        $this->generateLink($menu, $name, $route, $customUrl);
                break;

                case 'admin':
                    if($this->auth->isGranted('ROLE_ADMIN'))
                        $this->generateLink($menu, $name, $route, $customUrl);
                break;

                case 'redact':
                    if($this->auth->isGranted('ROLE_REDACT'))
                        $this->generateLink($menu, $name, $route, $customUrl);
                break;

                case 'everyone':
                    $this->generateLink($menu, $name, $route, $customUrl);
                break;

                case 'nologin':
                    if(!$this->auth->isGranted('ROLE_USER'))
                        $this->generateLink($menu, $name, $route, $customUrl);
                break;
            }
        }

        return $menu;
    }
    
    private function generateLink($menu, $name, $route, $customUrl)
    { 
        if($customUrl)
            $menu->addChild($name, ['uri' => $customUrl]);
        else
            $menu->addChild($name, ['route' => $route]);
        //return $menu;
    }
}
?>
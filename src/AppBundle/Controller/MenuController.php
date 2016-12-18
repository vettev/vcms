<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Menu;
use AppBundle\Form\MenuType;

class MenuController extends Controller
{
    /**
     * @Route("admin/menu/", name="menu_manage")
     */
    public function menuManageAction(Request $request)
    {
        $links = $this->getDoctrine()->getRepository('AppBundle:Menu')->findAll();
        $link = new Menu();
        $form = $this->createForm(MenuType::class, $link);
        $form->handleRequest($request);
        if($form->isValid()) {
            $customRoute = $request->get('customRoute');
            if($customRoute)
            {
                $link->setRoute(null);
                $customUrl = $request->get('customUrl');
                if($customUrl)
                    $link->setCustomUrl($customUrl);
                else
                {
                    $this->addFlash('error', 'Route/URL is invalid.');
                    return $this->redirectToRoute('menu_manage');
                }
            }
            else
                $link->setCustomUrl(null);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($link);
            $em->flush();
            $this->addFlash('notice', 'Link successfully added');
        }

        return $this->render('admin/menu.html.twig', ['links' => $links, 'form' => $form->createView()]);
    }
    /**
     * @Route("admin/menu/{id}/remove", name="link_remove")
     */
    public function removeAction($id, Request $request)
    {
        $link = $this->getDoctrine()->getRepository('AppBundle:Menu')->findOneById($id);

        if(!$link)
            return $this->redirectToRoute('menu_manage');

        $em = $this->getDoctrine()->getManager();
        $em->remove($link);
        $em->flush();
        $this->addFlash('notice', 'Link successfully removed');       

        return $this->redirectToRoute('menu_manage');
    }
    private function convertController(\Symfony\Component\Routing\Route $route)
    {
        $nameParser = $this->get('controller_name_converter');
        if ($route->hasDefault('_controller')) {
            try {
                $route->setDefault('_controller', $nameParser->build($route->getDefault('_controller')));
            } catch (\InvalidArgumentException $e) {
            }
        }
    }

}

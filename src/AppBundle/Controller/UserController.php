<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\UserType;
use AppBundle\Form\UserEditType;
use AppBundle\Form\LoginType;
use AppBundle\Entity\User;

class UserController extends Controller
{
    /**
     * @Route("/user/login", name="user_login")
     */
    public function loginAction(Request $request)
    {
        if($this->isGranted('ROLE_USER'))
            return $this->redirectToRoute('homepage');

        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);

	    $authenticationUtils = $this->get('security.authentication_utils');

	    $error = $authenticationUtils->getLastAuthenticationError();

	    $lastUsername = $authenticationUtils->getLastUsername();

	    return $this->render('user/login.html.twig', array(
	        'title' => 'Login',
            'error' => $error,
            'form'  => $form->createView()
	    ));
    }

    /**
     * @Route("/user/new", name="user_new")
     */
    public function addAction(Request $request)
    {
        if($this->isGranted('ROLE_USER'))
            return $this->redirectToRoute('homepage');

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
	       $password = $this->get('security.password_encoder')
	           ->encodePassword($user, $user->getPassword());
	        $user->setPassword($password);
            $user->setCreatedAt(new \Datetime());

            $role = $this->getDoctrine()->getRepository("AppBundle:Role")->findOneByName("User");
            $user->setRole($role);
                
	        $em = $this->getDoctrine()->getManager();
	        $em->persist($user);
	           $em->flush();

	        $this->addFlash('notice', 'Registration succesfull!');

	       return $this->redirectToRoute('user_login');
        }

        return $this->render('user/new.html.twig', [
            	'form' 	=> $form->createView(),
            	'title' => 'Registration',
            ]
        );
    }

    /**
     * @Route("/user/logout", name="user_logout")
     */
    public function logoutAction(Request $request)
    {
        return null;
    }

    /**
     * @Route("/user/{name}/edit", name="user_edit")
     */
    public function editAction($name, Request $request)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneByUsername($name);
        $role = $user->getRole();
        $avatar = $user->getAvatar();
        $user->setAvatar(null);

        if(!$this->isGranted('ROLE_ADMIN') && $this->getUser()->getId() !== $user->getId())
            return $this->redirectToRoute('user_show', ['name' => $name]);

        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if($form->isValid()) {

            $file = $form['avatar']->getData();
            if($file) {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('avatars_dir'), $fileName);
                $user->setAvatar($fileName);
            } else {
                $user->setAvatar($avatar);
            }
            if(!$form['role']->getData()) {
                $user->setRole($role);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('notice', 'Edition successful');
            
            return $this->redirectToRoute('user_show', ['name' => $name]);
        }

        return $this->render('user/edit.html.twig', ['user' => $user, 'form' => $form->createView()]);
    }

    /**
     * @Route("/user/{name}/{display}/{page}", name="user_show")
     */
    public function showAction(Request $request, $name, $display = "posts", $page = 1)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneByUsername($name);
		if(!$user)
			return $this->redirectToRoute('homepage');

		$paginator = $this->get('knp_paginator');
        if($display == "posts") {
			$repo = $this->getDoctrine()->getRepository('AppBundle:Post');
			$query = $repo->getPostsByUserId($user->getId());
			$pagination = $paginator->paginate($query, $page, 4);
		} elseif($display == "comments") {
			$repo = $this->getDoctrine()->getRepository('AppBundle:Comment');
			$query = $repo->getCommentsByUserId($user->getId());	
			$pagination = $paginator->paginate($query, $page, 10);
		} else
			return $this->redirectToRoute('homepage');
				
        return $this->render('user/show.html.twig', ['user' => $user, 'pagination' => $pagination, 'display' => $display]);
    }
}

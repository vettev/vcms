<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\PostType;
use AppBundle\Entity\Post;

class PostController extends Controller
{
    /**
     * @Route("/post/new", name="post_new")
     */
    public function newAction(Request $request)
    {
        if(!$this->isGranted('ROLE_REDACT')) {
            return $this->redirectToRoute('homepage');
        }

    	$post = new Post();
        $user = $this->getDoctrine()->getRepository('AppBundle:User')
        ->findOneById($this->getUser()->getId());

    	$form = $this->createForm(PostType::class, $post);
    	$form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $file = $post->getImage();
            if($file) {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('post_imgs_dir'), $fileName);
                $post->setImage($fileName);
            }
            $post->setCreatedAt(new \Datetime());
            $post->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $this->addFlash('notice', 'Post added successfully');
            
            return $this->redirectToRoute('blog');
        }

        return $this->render('post/new.html.twig', ['form' => $form->createView()
        ]);
    }
    /**
     * @Route("/post/{id}/show", name="post_show")
     */
    public function showAction($id, Request $request)
    {
        $post = $this->getDoctrine()->getRepository('AppBundle:Post')->findOneById($id);

        return $this->render('post/show.html.twig', ['post' => $post, 'single' => true]);
    }

    /**
     * @Route("/post/{id}/edit", name="post_edit")
     */
    public function editAction($id, Request $request)
    {
        if(!$this->isGranted('ROLE_USER'))
            return $this->redirectToRoute('post_show', ['id' => $id]);

        $post = $this->getDoctrine()->getRepository('AppBundle:Post')
        ->findOneById($id);

        $user = $this->getDoctrine()->getRepository('AppBundle:User')
        ->findOneById($this->getUser()->getId());
        $image = $post->getImage();
        $post->setImage(null);

        if(!$this->isGranted('ROLE_ADMIN') && $user->getId() !== $post->getUser()->getId()) {
            return $this->redirectToRoute('post_show', ['id' => $id]);
        }


        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $file = $post->getImage();
            if($file) {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('post_imgs_dir'), $fileName);
                $post->setImage($fileName);
            }
            else
                $post->setImage($image);

            $post->setCreatedAt(new \Datetime());
            $post->setUser($post->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $this->addFlash('notice', 'Post edit successfully');
            
            return $this->redirectToRoute('homepage');
        }

        return $this->render('post/edit.html.twig', ['form' => $form->createView()
        ]);
    }

    /**
     * @Route("/posts/category/{id}/page{page}", name="posts_by_category", requirements={"id": "\d+", "page": "\d+"})
     */
    public function listByCategory($id, Request $request, $page = 1)
    {
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->findOneById($id);
      
        if(!$category || $category->getName() == 'Page')
            return $this->redirectToRoute('blog');
      
        $limit = 4;
        $repo = $this->getDoctrine()->getRepository('AppBundle:Post');
        $query = $repo->getPostsByCategoryId($id);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, $limit);

        return $this->render('main/blog.html.twig', ['pagination' => $pagination]);
    }

    /**
     * @Route("/page/{id}/show", name="page_show")
     */
    public function showPageAction($id, Request $request, $full = true)
    {
        $page = $this->getDoctrine()->getRepository('AppBundle:Category')->findOneByName('Page');
        $post = $this->getDoctrine()->getRepository('AppBundle:Post')->findOneBy(['categoryId' => $page->getId(), 'id' => $id]);

        if(!$post)
            return $this->redirectToRoute('homepage');

        if($full)
            return $this->render('post/page.html.twig', ['post' => $post, 'full' => $full]);
        else
            return $this->render('templates/page.html.twig', ['post' => $post]);
    }

    /**
     * @Route("/post/{id}/remove", name="post_remove")
     */
    public function removeAction($id, Request $request)
    {
        $post = $this->getDoctrine()->getRepository('AppBundle:Post')
        ->findOneById($id);
        $settings = $this->getDoctrine()->getRepository('AppBundle:Settings')->findOneBy([]);
        if($settings->getHomepage() == $post->getId())
            $settings->setHomepage(0);

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->persist($settings);
        $em->flush();
        $this->addFlash('notice', 'Post removed');

        return $this->redirectToRoute('homepage');
    }

}

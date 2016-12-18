<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;

class CommentController extends Controller
{
    /**
     * @Route("post/{postId}/comment/new", name="comment_new")
     */
    public function newAction($postId, Request $request)
    {
    	if(!$this->isGranted('ROLE_USER'))
    		return $this->redirectToRoute('homepage');

    	$user = $this->getDoctrine()->getRepository('AppBundle:User')
    	->findOneById($this->getUser()->getId());
    	$post = $this->getDoctrine()->getRepository('AppBundle:Post')->findOneById($postId);

    	$comment = new Comment();
    	$form = $this->createForm(CommentType::class, $comment);
    	$form->handleRequest($request);
    	if($form->isValid() && $form->isSubmitted()) {
    		$comment->setCreatedAt(new \Datetime());
    		$comment->setUser($user);
    		$comment->setPost($post);

    		$em=$this->getDoctrine()->getManager();
    		$em->persist($comment);
    		$em->flush();

            if($request->isXmlHttpRequest())
                return $this->render('templates/comment.html.twig', ['comment' => $comment]);
            else
    		  return $this->redirectToRoute('post_show', ['id' => $postId]);
    	}

        return $this->render('comment/new.html.twig', ['form' => $form->createView(), 'postId' => $postId]);
    }

    /**
     * @Route("comment/{id}/remove", name="comment_remove")
     */
    public function deleteAction($id, Request $request)
    {
    	$comment = $this->getDoctrine()->getRepository('AppBundle:Comment')->findOneById($id);
    	$user = $this->getUser()->getUsername();
    	if(!$this->isGranted('ROLE_ADMIN') && $comment->getUser()->getUsername() != $user )
    		return $this->redirectToRoute('post_show', ['id' => $comment->getPost()->getId()]);

    	$em = $this->getDoctrine()->getManager();
    	$em->remove($comment);
    	$em->flush();

    	return $this->redirectToRoute('post_show', ['id' => $comment->getPost()->getId()]);
    }

    /**
     * @Route("comment/{id}/edit", name="comment_edit")
     */
    public function editAction($id, Request $request)
    {
    	$comment = $this->getDoctrine()->getRepository('AppBundle:Comment')->findOneById($id);
    	$user = $this->getUser()->getUsername();
    	if(!$this->isGranted('ROLE_ADMIN') && $comment->getUser()->getUsername() != $user )
    		return $this->redirectToRoute('post_show', ['id' => $comment->getPost()->getId()]);

    	$form = $this->createForm(CommentType::class, $comment);
    	$form->handleRequest($request);

    	if($form->isValid() && $form->isSubmitted()) {
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($comment);
    		$em->flush();

            if($request->isXmlHttpRequest())
                return $this->render('templates/comment.html.twig', ['comment' => $comment]);
            else
                return $this->redirectToRoute('post_show', ['id' => $comment->getPost()->getId()]); 
    	}
    	return $this->render('comment/edit.html.twig', ['form' => $form->createView(), 'id' => $id]);
    }

    /**
     * @Route("comment/{id}/show", name="comment_show")
     */
    public function showAction($id, Request $request)
    {
        $comment = $this->getDoctrine()->getRepository('AppBundle:Comment')->findOneById($id);

        return $this->render('comment/show.html.twig', ['comment' => $comment]);
    }
}

<?php

namespace Factu\UserBundle\Controller;

use Factu\UserBundle\Entity\User;
use Factu\UserBundle\Form\UserType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends Controller
{

    public function indexAction()
	{
	    $listUsers = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuUserBundle:User')
	      ->findAll()
	    ;

    	return $this->render('FactuUserBundle:User:index.html.twig', array(
	      'listUsers' => $listUsers
	    ));
    }

    public function viewAction($id)
    {
	    $user = $this->getDoctrine()
	      ->getManager()
	      ->getRepository('FactuUserBundle:User')
	      ->find($id)
	    ;

    	return $this->render('FactuUserBundle:User:view.html.twig', array(
	      'user' => $user
	    ));
    }

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
    public function addAction(Request $request)
    {
    	$user = new User();

		$form = $this->get('form.factory')->create(new UserType, $user);

	    if ($form->handleRequest($request)->isValid()) {
			$encoder = $this->container->get('security.password_encoder');
			$password = $user->getPassword();
			$encoded = $encoder->encodePassword($user, $password);
			$user->setPassword($encoded);

	    	$em = $this->getDoctrine()->getManager();
		    $em->persist($user);
		    $em->flush();

            // Envoi du mail
            $message = \Swift_Message::newInstance()
                ->setSubject('Création de compte')
                ->setFrom('facturation@noreply.com')
                ->setTo($user->getEmail())
                ->setBody($this->renderView('FactuUserBundle:User:email.txt.twig', array(
                  'user' => $user, 
                  'password' => $password
                )))
            ;
            $this->get('mailer')->send($message);

		    $request->getSession()->getFlashBag()->add('notice', 'Utilisateur bien enregistré.');

		    return $this->redirect($this->generateUrl('user_view', array('id' => $user->getId())));
	    }

	    return $this->render('FactuUserBundle:User:add.html.twig', array(
	      'form' => $form->createView(),
	    ));
    }

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function editAction($id, Request $request)
	{
	    $em = $this->getDoctrine()->getManager();
	    $user = $em->getRepository('FactuUserBundle:User')->find($id);
	    $user->setPassword("");

	    if ($user == null) {
	      throw $this->createNotFoundException("L'utilisateur d'id " . $id . " n'existe pas.");
	    }

		$form = $this->get('form.factory')->create(new UserType, $user);

		if ($form->handleRequest($request)->isValid()) {
			$encoder = $this->container->get('security.password_encoder');
			$encoded = $encoder->encodePassword($user, $user->getPassword());
			$user->setPassword($encoded);

	    	$em = $this->getDoctrine()->getManager();
		    $em->persist($user);
		    $em->flush();

		    $request->getSession()->getFlashBag()->add('notice', 'Utilisateur bien enregistré.');

		    return $this->redirect($this->generateUrl('user_view', array('id' => $user->getId())));
	    }

	    return $this->render('FactuUserBundle:User:edit.html.twig', array(
	      'form' => $form->createView(), 'user' => $user
	    ));
	}

	public function editMyProfilAction(Request $request)
	{
	    $em = $this->getDoctrine()->getManager();
	    $user = $this->getUser();
	    $user->setPassword("");

	    if ($user == null) {
	      throw $this->createNotFoundException("L'utilisateur d'id " . $id . " n'existe pas.");
	    }

		$form = $this->get('form.factory')->create(new UserType, $user);

		if ($form->handleRequest($request)->isValid()) {
			$encoder = $this->container->get('security.password_encoder');
			$encoded = $encoder->encodePassword($user, $user->getPassword());
			$user->setPassword($encoded);

	    	$em = $this->getDoctrine()->getManager();
		    $em->persist($user);
		    $em->flush();

		    $request->getSession()->getFlashBag()->add('notice', 'Profil enregistré.');

		    return $this->redirect($this->generateUrl('home'));
	    }

	    return $this->render('FactuUserBundle:User:edit_profil.html.twig', array(
	      'form' => $form->createView(), 'user' => $user
	    ));
	}


	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
	public function deleteAction($id, Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$user = $em->getRepository('FactuUserBundle:User')->find($id);

		if (null === $user) {
		  throw new NotFoundHttpException("L'utilisateur d'id " . $id . " n'existe pas.");
		}

		$userConnected = $this->getUser();
		if ($userConnected->getId() == $user->getId()) {
		  throw new NotFoundHttpException("Vous ne pouvez pas supprimer votre propre compte.");
		}

		$form = $this->createFormBuilder()->getForm();

		if ($form->handleRequest($request)->isValid()) {
		  $em->remove($user);
		  $em->flush();

		  $request->getSession()->getFlashBag()->add('info', "L'utilisateur' a bien été supprimé.");

		  return $this->redirect($this->generateUrl('user_home'));
		}

		return $this->render('FactuUserBundle:User:delete.html.twig', array(
		  'user' => $user,
		  'form'   => $form->createView()
		));
	}

}
<?php
// src/OC/UserBundle/Controller/SecurityController.php;

namespace Factu\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
      // Si le visiteur est déjà identifié, on le redirige vers l'accueil
      if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
        return $this->redirect($this->generateUrl('product_home'));
      }

      $session = $request->getSession();

      // On vérifie s'il y a des erreurs d'une précédente soumission du formulaire
      if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
        $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
      } else {
        $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        $session->remove(SecurityContext::AUTHENTICATION_ERROR);
      }

      return $this->render('FactuUserBundle:Security:login.html.twig', array(
        // Valeur du précédent nom d'utilisateur entré par l'internaute
        'last_username' => $session->get(SecurityContext::LAST_USERNAME),
        'error'         => $error,
      ));
    }

    public function forgetAction(Request $request)
    {
      $form = $this->get('form.factory')
                 ->createBuilder('form')
                 ->add('login',    'text')
                 ->add('envoyer',    'submit')
                 ->getForm();
       
      if ($request->getMethod() == 'POST') {
        $form->bind($request);
        
        if ($form['login']->getData() != null) {
          $login = $form['login']->getData();

          $user = $this->getDoctrine()
            ->getManager()
            ->getRepository('FactuUserBundle:User')
            ->findOneByLogin($login)
          ;

          if ($user != null && $user->getEmail() != null) {
            $newPassword = $this->generateRandomString();

            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $newPassword);
            $user->setPassword($encoded);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // Envoi du mail
            $message = \Swift_Message::newInstance()
                ->setSubject('Votre nouveau mot de passe')
                ->setFrom('facturation@noreply.com')
                ->setTo($user->getEmail())
                ->setBody($this->renderView('FactuUserBundle:Security:email.txt.twig', array(
                  'user' => $user, 
                  'newPassword' => $newPassword
                )))
            ;
            $this->get('mailer')->send($message);

            $request->getSession()->getFlashBag()->add('notice', 'Nouveau mot de passe envoyé par mail.');
            return $this->render('FactuUserBundle:Security:login.html.twig');
          } else {

            if ($user == null) {
              $request->getSession()->getFlashBag()->add('error', "Le login '" . $login . "' n'existe pas.");
            } else if ($user->getEmail() != null) {
              $request->getSession()->getFlashBag()->add('error', "L'utilisateur n'a pas défini d'email. Merci de contacter l'administrateur.");
            }
          }
        } 

      }

      return $this->render('FactuUserBundle:Security:forget.html.twig', array(
          'form' => $form->createView(),
      ));  
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
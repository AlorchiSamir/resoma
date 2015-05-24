<?php

namespace Resoma\ProfilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Resoma\ProfilBundle\Entity\Abonnement;

class AbonnementController extends Controller
{

  public function readAction(){
    $selfUser = $this->container->get('security.context')->getToken()->getUser();
    $repository = $this->getDoctrine()->getManager()->getRepository('ResomaProfilBundle:Parametre');
    $parametre = $repository->findOneBy(array('user' => $selfUser));
    if(!empty($parametre) && $parametre->getDisabled() == true){            
      return $this->render('ResomaSiteBundle:Site:disabled.html.twig');      
    }
    else{
      $em = $this->getDoctrine()->getManager();
      $abonnements = $em->getRepository('ResomaProfilBundle:Abonnement')->findBy(array('user1' => $selfUser));
      foreach($abonnements as &$abonnement){    
        $parametre = $em->getRepository('ResomaProfilBundle:Parametre')->findOneBy(array('user' => $abonnement->getUser2()->getId()));
        if(empty($parametre)){
          $abonnement->getUser2()->setAvatar('avatar/defaut.jpg');
          $abonnement->getUser2()->setDescription('');
        }
        else{
          if($parametre->getAvatar() == ''){
            $abonnement->getUser2()->setAvatar('avatar/defaut.jpg');
          }
          else{
            $abonnement->getUser2()->setAvatar('avatar/'.$abonnement->getUser2()->getId().'/'.$parametre->getAvatar());
          }
          $abonnement->getUser2()->setDescription($parametre->getDescription());
        }
      }
      return $this->render('ResomaProfilBundle:Abonnement:read.html.twig', array('abonnements' => $abonnements));
    }
  }

  public function boutonAction($user){
    $selfUser = $this->container->get('security.context')->getToken()->getUser();
    $em = $this->getDoctrine()->getManager();
    $abonnement = $em->getRepository('ResomaProfilBundle:Abonnement')->findBy(array('user1' => $selfUser, 'user2' => $user));
    if(empty($abonnement)){
      $acces = false;
    }
    else { $acces = true; }
    $user = $em->getRepository('ResomaUserBundle:User')->find($user);
    return $this->render('ResomaProfilBundle:Abonnement:boutonAbo.html.twig', array('user' => $user, 'acces' => $acces));
  }

  public function addAction($ami = false){
    $request = $this->container->get('request');
    if($request->isXmlHttpRequest()){
      $user = (int)$request->request->get('user');
    	$abonnement = new Abonnement();
    	$em = $this->getDoctrine()->getManager();
      $user = $em->getRepository('ResomaUserBundle:User')->find($user);
    	$abonnement->setUser1($this->container->get('security.context')->getToken()->getUser());
    	$abonnement->setUser2($user);
    	$abonnement->setAjout(new \DateTime());
      $abonnement->setAmi($ami);
    	$em = $this->getDoctrine()->getManager();
      $em->persist($abonnement);
      $em->flush();
      $notificateur = $this->container->get('resoma_notification.notificateur');
      $texte = $this->container->get('security.context')->getToken()->getUser()." s'est abonné à votre profil.";
      $notificateur->addNotif($user, $texte);
      //return $this->redirect($this->generateUrl('resoma_profil_profil', array('user' => $user->getId())));
    }
  }  

  public function deleteAction(){
    $selfUser = $this->container->get('security.context')->getToken()->getUser();
    $request = $this->container->get('request');
    if($request->isXmlHttpRequest()){
      $user = (int)$request->request->get('user');
      $em = $this->getDoctrine()->getManager();
      $abonnements = $em->getRepository('ResomaProfilBundle:Abonnement')->findBy(array('user1' => $selfUser, 'user2' => $user));
      foreach($abonnements as $abonnement){
        $em->remove($abonnement);
      }
      $em->flush();
    }
  }
    
}

?>
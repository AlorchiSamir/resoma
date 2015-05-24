<?php

namespace Resoma\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SiteController extends Controller
{
  public function indexAction(){
  	if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')){
      $user = $this->container->get('security.context')->getToken()->getUser();
      $notificateur = $this->container->get('resoma_notification.notificateur');
      $nombre = $notificateur->getNombre($user);
  		return $this->render('ResomaSiteBundle:Site:accueil.html.twig', array('nombreNotif' => $nombre));
  	}
  	else{
    	return $this->render('ResomaSiteBundle:Site:index.html.twig');
	  }
  }

  public function accueilAction(){
    $user = $this->container->get('security.context')->getToken()->getUser();
    $repository = $this->getDoctrine()->getManager()->getRepository('ResomaProfilBundle:Parametre');
    $parametre = $repository->findOneBy(array('user' => $user));
    if(!empty($parametre) && $parametre->getDisabled() == true){            
        return $this->render('ResomaSiteBundle:Site:disabled.html.twig');      
    }
    else{
      $notificateur = $this->container->get('resoma_notification.notificateur');
      $nombre = $notificateur->getNombre($user);
      return $this->render('ResomaSiteBundle:Site:accueil.html.twig', array('nombreNotif' => $nombre));
    }
  }

  public function logAction(){
    return $this->render('ResomaSiteBundle:Site:changelog.html.twig');
  }

  public function tutoAction(){
    $user = $this->container->get('security.context')->getToken()->getUser();
    $notificateur = $this->container->get('resoma_notification.notificateur');
    $nombre = $notificateur->getNombre($user);
    return $this->render('ResomaSiteBundle:Site:tutoriel.html.twig', array('nombreNotif' => $nombre));
  }
}

?>
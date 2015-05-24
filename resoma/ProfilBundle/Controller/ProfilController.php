<?php

namespace Resoma\ProfilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfilController extends Controller
{
  public function indexAction($user = 0){
    $selfUser = $this->container->get('security.context')->getToken()->getUser();
    $repository = $this->getDoctrine()->getManager()->getRepository('ResomaProfilBundle:Parametre');
    $parametre = $repository->findOneBy(array('user' => $selfUser));
    if(!empty($parametre) && $parametre->getDisabled() == true){            
        return $this->render('ResomaSiteBundle:Site:disabled.html.twig');      
    }
    else{
    	if($user == 0){
    		$user = $selfUser;
    	}
    	else{
    		$em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('ResomaUserBundle:User')->find($user);
    	}
    	if($selfUser->getId() == $user->getId()){
    		$self = true;
    	}
    	else{
    		$self = false;
    	}
      $em = $this->getDoctrine()->getManager();
      $notificateur = $this->container->get('resoma_notification.notificateur');
      $nombre = $notificateur->getNombre($selfUser);
      $parametre = $em->getRepository('ResomaProfilBundle:Parametre')->findOneBy(array('user' => $user));
      if(empty($parametre)){
        $description = '';
        $user->setAvatar('avatar/defaut.jpg');
      }
      else{
        if($parametre->getAvatar() == ''){
          $user->setAvatar('avatar/defaut.jpg');
        }
        else{
          $user->setAvatar('avatar/'.$user->getId().'/'.$parametre->getAvatar());
        }      
        $description = $parametre->getDescription();
      }
      return $this->render('ResomaProfilBundle:Profil:index.html.twig', array('user' => $user, 'self' => $self, 'nombreNotif' => $nombre, 'description' => $description));
    }
  }

  public function barreAction(){
    $user = $this->container->get('security.context')->getToken()->getUser();
    $em = $this->getDoctrine()->getManager();
    $parametre = $em->getRepository('ResomaProfilBundle:Parametre')->findOneBy(array('user' => $user));
    if(empty($parametre)){
      $user->setAvatar('avatar/defaut.jpg');
    }
    else{
      if($parametre->getAvatar() == ''){
        $user->setAvatar('avatar/defaut.jpg');
      }
      else{
        $user->setAvatar('avatar/'.$user->getId().'/'.$parametre->getAvatar());
      } 
    }
    $notificateur = $this->container->get('resoma_notification.notificateur');
    $nombre = $notificateur->getNombre($user);
    return $this->render('ResomaProfilBundle:Profil:barreUser.html.twig', array('nombreNotif' => $nombre));
  } 

}

?>
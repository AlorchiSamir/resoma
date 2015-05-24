<?php

namespace Resoma\ProfilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Resoma\ProfilBundle\Entity\Parametre;
use Resoma\ProfilBundle\Form\ParametreType;

class ParametreController extends Controller
{
  public function indexAction(){
    $change = false;
    $flag = false;
    $user = $this->container->get('security.context')->getToken()->getUser();
    $repository = $this->getDoctrine()->getManager()->getRepository('ResomaProfilBundle:Parametre');
    $parametre = $repository->findOneBy(array('user' => $user ));
    if(empty($parametre)){
      $flag = true;
      $parametre = new Parametre();
    }    
    $form = $this->createForm(new ParametreType, $parametre); 
    $request = $this->get('request'); 
    if($request->getMethod() == 'POST'){
      $change = true;
      $form->bind($request);
      if ($form->isValid()){
        $em = $this->getDoctrine()->getManager();
        if($parametre->getFile() != null){
          $mt = $parametre->getFile()->getClientMimeType(); 
        }
        else{
          $mt = ''; 
        }       
        if($flag){
          $parametre->setUser($user);          
        }
        if($mt == 'image/gif' || $mt == 'image/png' || $mt == 'image/jpeg'){
          $parametre->upload();
        }
        else{
          $parametreTemp = $repository->findOneBy(array('user' => $user ));
          if(empty($parametreTemp)){
            $parametre->setAvatar('');
          }
          else{
            $parametre->setAvatar($parametreTemp->getAvatar());
          }
        }     
        $em->persist($parametre);
        $em->flush();
      }
    }
    return $this->render('ResomaProfilBundle:Parametre:index.html.twig', array('form' => $form->createView(), 'change' => $change));    
  } 
}

?>
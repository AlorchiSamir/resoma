<?php

namespace Resoma\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Resoma\ChatBundle\Entity\Message;
use Resoma\ChatBundle\Form\MessageType;

class MessageController extends Controller
{

  public function indexAction(){
    $user = $this->container->get('security.context')->getToken()->getUser();
    $repository = $this->getDoctrine()->getManager()->getRepository('ResomaProfilBundle:Parametre');
    $parametre = $repository->findOneBy(array('user' => $user));
    if(!empty($parametre) && $parametre->getDisabled() == true){            
        return $this->render('ResomaSiteBundle:Site:disabled.html.twig');      
    }
    else{
      return $this->render('ResomaChatBundle::layout.html.twig');
    }    
  }

  public function readAction(){
    $repository = $this->getDoctrine()
                     ->getManager()
                     ->getRepository('ResomaChatBundle:Message');    
    $messages = $repository->findBy(array(), array('dateEnvoi' => 'asc'));    
    foreach($messages as &$message){
      $message->setDateEnvoi(date_format($message->getDateEnvoi(), 'h:i:s'));
      $em = $this->getDoctrine()->getManager();
      $parametre = $em->getRepository('ResomaProfilBundle:Parametre')->findOneBy(array('user' => $message->getAuteur()->getId()));
      if(empty($parametre)){
        $message->getAuteur()->setAvatar('avatar/defaut.jpg');
      }
      else{
        if($parametre->getAvatar() == ''){
          $message->getAuteur()->setAvatar('avatar/defaut.jpg');
        }
        else{
          $message->getAuteur()->setAvatar('avatar/'.$message->getAuteur()->getId().'/'.$parametre->getAvatar());
        }
      }
    }
    return $this->container->get('templating')->renderResponse('ResomaChatBundle:Message:read.html.twig', array('messages' => $messages));
  }

  public function addAction(){
    $message = new Message();
    $request = $this->container->get('request');
    if($request->isXmlHttpRequest()){
        $texte = $request->request->get('texte');     
        $id_auteur = $this->container->get('security.context')->getToken()->getUser();
        $message->setTexte($texte);
        $message->setAuteur($id_auteur);
        $message->setDateEnvoi(new \DateTime());
        $message->setAffiche(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();
    }
  }

  public function deleteAction(){
    $request = $this->container->get('request');
    if($request->isXmlHttpRequest()){
      $message = (int)$request->request->get('message');
      $em = $this->getDoctrine()->getManager();
      $message = $em->getRepository('ResomaChatBundle:Message')->find($message);
      $em->flush();
      $em->remove($message);
      $em->flush();
    }
  } 

} 

?>
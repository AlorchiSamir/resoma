<?php

namespace Resoma\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
  public function readAction(){
    $user = $this->container->get('security.context')->getToken()->getUser();
    $users = array();
    $repository = $this->getDoctrine()->getManager()->getRepository('ResomaUserBundle:User');
    $repository2 = $this->getDoctrine()->getManager()->getRepository('ResomaProfilBundle:Abonnement');
    $abonnements = $repository2->findAbonnement($user->getId());
    //var_dump($abonnements);
    //$users = $repository->findBy(array(), array('lastLogin' => 'desc'));
    foreach($abonnements as &$abonnement){
      $users[] = $repository->find($abonnement->getUser2()->getId());
    }  
    $cpt = 0;
    foreach($users as &$user){
    	$user->setDerniereConnexion($user->getIntervalle());
      $cpt++;
    }
    $usersLimit = array();
    if($cpt < 10){ $j = $cpt; }
    else{ $j = 10; }
    for($i = 0; $i < $j; $i++){
      $usersLimit[$i] = $users[$i];
    }
    return $this->container->get('templating')->renderResponse('ResomaUserBundle:User:read.html.twig', array('users' => $usersLimit));
  } 

  public function readOneAction(){
    $request = $this->container->get('request');
    if($request->isXmlHttpRequest()){
      $nom = $request->request->get('nom');
      $em = $this->getDoctrine()->getManager();
      $users = $em->getRepository('ResomaUserBundle:User')->findUser($nom);
      foreach($users as &$user){
    	$user->setDerniereConnexion($user->getIntervalle());
      }      
      return $this->container->get('templating')->renderResponse('ResomaUserBundle:User:readOne.html.twig', array('users' => $users));
    }
  }
}

?>
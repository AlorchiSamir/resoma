<?php

namespace Resoma\PublicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Resoma\PublicationBundle\Entity\Approbation;

class ApprobationController extends Controller
{
  public function addAction(){
    $selfUser = $this->container->get('security.context')->getToken()->getUser();
  	$approbation = new Approbation();
  	$em = $this->getDoctrine()->getManager();
    $request = $this->container->get('request');
    if($request->isXmlHttpRequest()){
      $publication = (int)$request->request->get('publication');
      $publication = $em->getRepository('ResomaPublicationBundle:Publication')->find($publication);
    	$approbation->setUser($this->container->get('security.context')->getToken()->getUser());
    	$approbation->setPublication($publication);
    	$approbation->setDateAppro(new \DateTime());
    	$em = $this->getDoctrine()->getManager();
      $em->persist($approbation);
      $em->flush();
      if($selfUser->getId() != $publication->getAuteur()->getId()){
        $notificateur = $this->container->get('resoma_notification.notificateur');
        $texte = $selfUser." a aimé votre publication.";
        $notificateur->addNotif($publication->getAuteur(), $texte);
      }
    }
  }

  public function deleteAction(){
    $user = $this->container->get('security.context')->getToken()->getUser();
    $em = $this->getDoctrine()->getManager();
    $request = $this->container->get('request');
    if($request->isXmlHttpRequest()){
      $publication = (int)$request->request->get('publication');
      $approbations = $em->getRepository('ResomaPublicationBundle:Approbation')->findBy(array('publication' => $publication, 'user' => $user));
      foreach($approbations as $approbation){
        $em->remove($approbation);
      }
      $em->flush();
    }
  }

  public function readAction(){
    $selfUser = $this->container->get('security.context')->getToken()->getUser();
    $em = $this->getDoctrine()->getManager();
    $abonnements = $em->getRepository('ResomaProfilBundle:Abonnement')->findBy(array('user1' => $selfUser));
    return $this->render('ResomaProfilBundle:Abonnement:read.html.twig', array('abonnements' => $abonnements));
  }
    
}

?>
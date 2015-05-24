<?php

namespace Resoma\PublicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Resoma\PublicationBundle\Entity\Liste;
use Resoma\PublicationBundle\Form\ListeType;

class ListeController extends Controller
{

  public function readListeAction($nombre = 0){
    $repository = $this->getDoctrine()->getManager()->getRepository('ResomaPublicationBundle:Liste');
    if($nombre == 0){
      $listes = $repository->findBy(array(), array('id' => 'DESC'),10);
    }
    return $this->render('ResomaPublicationBundle:Liste:readListe.html.twig', array('listes' => $listes));
  }

  public function readAction($liste){
    $user2 = $this->container->get('security.context')->getToken()->getUser();
    $repository = $this->getDoctrine()->getManager()->getRepository('ResomaPublicationBundle:Publication');
    $repository2 = $this->getDoctrine()->getManager()->getRepository('ResomaPublicationBundle:Liste');
    $liste = $repository2->find($liste);
    $repository2 = $this->getDoctrine()->getManager()->getRepository('ResomaPublicationBundle:Listage');
    $listages = $repository2->findListage($liste);
    $publications = array();
    foreach($listages as &$listage){
      $publication = $repository->find($listage->getPublication());
      $publications[] = $publication;
    }
    $self = true;
    foreach($publications as &$publication){
      $repository = $this->getDoctrine()->getManager()->getRepository('ResomaPublicationBundle:Approbation');
      $publication->urllink();
      $publication->calculScore($repository);
      $approbation = $repository->findOneBy(array('user' => $user2, 'publication' => $publication));
      $publication->setListe($liste);      
      if(empty($approbation)){ $publication->setApprouve(0); }
      else { $publication->setApprouve($approbation->getId()); }
      if($self){ $publication->setPubliable(true); }
      else{
        $abonnement = $repository2->findOneBy(array('user1' => $user2, 'user2' => $publication->getAuteur()));
        if(empty($abonnement) && $publication->getPublic()){ $publication->setPubliable(true); }
        else if(empty($abonnement) && !$publication->getPublic()){ $publication->setPubliable(false); }        
        else if($abonnement->getAmi() || $publication->getPublic()){ $publication->setPubliable(true); }
        else{ $publication->setPubliable(false); } 
      }    
      $publication->setDatePubli(date_format($publication->getDatePubli(), 'd/m/Y'));
      $em = $this->getDoctrine()->getManager();
      $parametre = $em->getRepository('ResomaProfilBundle:Parametre')->findOneBy(array('user' => $publication->getAuteur()->getId()));
      if(empty($parametre)){
        
      }
      else{
        $publication->getAuteur()->setAvatar($publication->getAuteur()->getId().'/'.$parametre->getAvatar());
      }
    }   
    return $this->render('ResomaPublicationBundle:Liste:readPubli.html.twig', array('publications' => $publications, 'self' => $self, 'liste' => $liste));
  }

  public function addAction(){
    $liste = new Liste();
    $form = $this->createForm(new ListeType, $liste);
    $request = $this->get('request');
    if($request->getMethod() == 'POST'){
      $form->bind($request);
      if ($form->isValid()){ 
        $liste->setDateCreation(new \DateTime());
        $em = $this->getDoctrine()->getManager();
        $em->persist($liste);
        $em->flush();
      }
      return $this->redirect($this->generateUrl('resoma_profil_index'));
    }       
    return $this->render('ResomaPublicationBundle:Liste:add.html.twig', array('form' => $form->createView(), 'liste' => $liste)); 
  }  

  public function readOneAction(){
    $request = $this->container->get('request');
    if($request->isXmlHttpRequest()){
      $nom = $request->request->get('nom');
      $type = $request->request->get('type');
      $em = $this->getDoctrine()->getEntityManager();
      $listes = $em->getRepository('ResomaPublicationBundle:Liste')->findListe($nom);
      if($type == 'lien'){
        return $this->container->get('templating')->renderResponse('ResomaPublicationBundle:Liste:read.html.twig', array('listes' => $listes, 'lien' => $type));
      }
      else{  
        return $this->container->get('templating')->renderResponse('ResomaPublicationBundle:Liste:read.html.twig', array('listes' => $listes));
      }
    }
  }
}

?>
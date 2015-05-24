<?php

namespace Resoma\PublicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Resoma\PublicationBundle\Entity\Commentaire;
use Resoma\PublicationBundle\Entity\Publication;
use Resoma\PublicationBundle\Form\CommentaireType;

class CommentaireController extends Controller
{

  public function readAction($objet=0){
    $self = $this->container->get('security.context')->getToken()->getUser();
    $repository = $this->getDoctrine()
                     ->getManager()
                     ->getRepository('ResomaPublicationBundle:Commentaire');
    $request = $this->container->get('request');
    if($request->isXmlHttpRequest()){
      $objet = (int)$request->request->get('publication');
    }
    $commentaires = $repository->findBy(array('publication' => $objet));
    $em = $this->getDoctrine()->getManager();    
    foreach($commentaires as &$commentaire){
      //$commentaire->setDatePubli(date_format($commentaire->getDatePubli(), 'd/m/Y'));
      $commentaire->urllink();
      $parametre = $em->getRepository('ResomaProfilBundle:Parametre')->findOneBy(array('user' => $commentaire->getAuteur()->getId()));
      if(empty($parametre)){
          
      }
      else{
        if($parametre->getAvatar() == ''){
          $commentaire->getAuteur()->setAvatar('avatar/defaut.jpg');
        }
        else{
          $commentaire->getAuteur()->setAvatar('avatar/'.$commentaire->getAuteur()->getId().'/'.$parametre->getAvatar());
        }
      }
    }    
    return $this->container->get('templating')->renderResponse('ResomaPublicationBundle:Commentaire:read.html.twig', array('commentaires' => $commentaires, 'self' => $self, 'objet' => $objet));
  }

  public function addAction($objet, $type){
    $commentaire = new Commentaire();
    $form = $this->createForm(new CommentaireType, $commentaire);
    $request = $this->get('request'); 
    if($request->getMethod() == 'POST'){
      $form->bind($request);
      if ($form->isValid()){
        $repository = $this->getDoctrine()
                     ->getManager()
                     ->getRepository('ResomaPublicationBundle:'.ucfirst($type));
        $objet = $repository->find($objet);
        $fct = 'set'.ucfirst($type);
        $commentaire->$fct($objet);        
        $id_auteur = $this->container->get('security.context')->getToken()->getUser();
        $commentaire->setAuteur($id_auteur);
        $commentaire->setDatePubli(new \DateTime());
        $em = $this->getDoctrine()->getManager();
        $em->persist($commentaire);
        $em->flush();
        $notificateur = $this->container->get('resoma_notification.notificateur');
        $texte = $id_auteur." a commenté votre publication.";
        if($objet->getAuteur() != $id_auteur){
          $notificateur->addNotif($objet->getAuteur(), $texte);
        }
      }
      //return $this->redirect($this->generateUrl('resoma_profil_profil', array('user' => $objet->getAuteur()->getId())));
      return $this->redirect($this->generateUrl('resoma_publication_readone', array('publication' => $objet->getId())));
    }        
    return $this->render('ResomaPublicationBundle:Commentaire:add.html.twig', array('form' => $form->createView(), 'commentaire' => $commentaire, 'objet' => $objet, 'type' => $type)); 
  } 

  public function updateAction($commentaire){
      $em = $this->getDoctrine()->getManager();
      $commentaires = $em->getRepository('ResomaPublicationBundle:Commentaire')->find($commentaire);
      $form = $this->createForm(new CommentaireType, $commentaires);
      $request = $this->get('request'); 
      if($request->getMethod() == 'POST'){
        $form->bind($request);  
        $em->persist($commentaires);
        $em->flush();
        return $this->redirect($this->generateUrl('resoma_publication_readone', array('publication' => $commentaires->getPublication()->getId())));
      }
      return $this->render('ResomaPublicationBundle:Commentaire:add.html.twig', array('commentaires' => $commentaires, 'form' => $form->createView()));
    }

    public function deleteAction(){
      $request = $this->container->get('request');
      if($request->isXmlHttpRequest()){
        $commentaire = (int)$request->request->get('commentaire');
        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository('ResomaPublicationBundle:Commentaire')->find($commentaire);
        $em->flush();
        $em->remove($commentaire);
        $em->flush();
      }
    }
}

?>
<?php

namespace Resoma\PublicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Resoma\PublicationBundle\Entity\Publication;
use Resoma\PublicationBundle\Form\PublicationType;
use Resoma\PublicationBundle\Entity\Liste;

class PublicationController extends Controller
{

  public function renderAction(){
    $request = $this->container->get('request');
    if($request->isXmlHttpRequest()){
      $objet = (int)$request->request->get('objet');
      $type = $request->request->get('type');
      return $this->container->get('templating')->renderResponse('ResomaPublicationBundle:Publication:update.html.twig', array('objet' => $objet, 'type' => $type));
    }
  }  

  public function readAction($user, $aside = false){
    $user2 = $this->container->get('security.context')->getToken()->getUser();
    $repository = $this->getDoctrine()->getManager()->getRepository('ResomaPublicationBundle:Publication');
    $repository2 = $this->getDoctrine()->getManager()->getRepository('ResomaProfilBundle:Abonnement');
    if($user == null){
      $abonnements = $repository2->findBy(array('user1' => $user2));
      $auteurs = array();
      foreach ($abonnements as $abonnement){
        $auteurs[] = $abonnement->getUser2();
      }
      $publications = $repository->findBy(array('auteur' => $auteurs), array('datePubli' => 'desc'));
      $self = false;
    }
    else if($user == 'all'){
      $publications = $repository->findBy(array(), array('datePubli' => 'desc'));
      $self = false;
      $user = null;
    }
    else{
      $publications = $repository->findBy(array('auteur' => $user), array('datePubli' => 'desc'));
      if($user2->getId() == $user->getId()){ $self = true; }
      else{ $self = false; }
    }
    foreach($publications as &$publication){
      $publication = $this->traitementPublication($publication, $user2, $self);
    }  
    return $this->render('ResomaPublicationBundle:Publication:read.html.twig', array('publications' => $publications, 'self' => $self, 'user' => $user, 'aside' => $aside));
  }

  public function readTriAction($aside = false){
    $user2 = $this->container->get('security.context')->getToken()->getUser();
    $request = $this->container->get('request');
    $repository = $this->getDoctrine()->getManager()->getRepository('ResomaPublicationBundle:Publication');
    $repository2 = $this->getDoctrine()->getManager()->getRepository('ResomaProfilBundle:Abonnement');
    if($request->isXmlHttpRequest()){
      $user = (int)$request->request->get('user');
      $type = $request->request->get('type');
      if($type == 'dateAsc'){
        $ordre = 'asc';
      }
      else{
        $ordre = 'desc';
      }
      $em = $this->getDoctrine()->getManager();  
      if($user == 0){
        $abonnements = $repository2->findBy(array('user1' => $user2));
        $auteurs = array();
        foreach ($abonnements as $abonnement){
          $auteurs[] = $abonnement->getUser2();
        }
        $publications = $repository->findBy(array('auteur' => $auteurs), array('datePubli' => $ordre));
        $self = false;
        $user = null;
      }
      else{
        $repository3 = $this->getDoctrine()->getManager()->getRepository('ResomaUserBundle:User');
        $user = $repository3->find($user);
        $publications = $repository->findBy(array('auteur' => $user), array('datePubli' => $ordre));
        if($user2->getId() == $user->getId()){ $self = true; }
        else{ $self = false; }
      }
      foreach($publications as &$publication){
        $publication = $this->traitementPublication($publication, $user2, $self);
      }  
      if($type == 'score'){
        usort($publications ,function($a, $b){
          return $a->getScore() < $b->getScore();
        }); 
      }
      else if($type == 'commentaire'){
        usort($publications ,function($a, $b){
          return $a->getCommentaire() < $b->getCommentaire();
        }); 
      }
      return $this->container->get('templating')->renderResponse('ResomaPublicationBundle:Publication:read.html.twig', array('publications' => $publications, 'self' => $self, 'user' => $user, 'aside' => $aside));    
    }
  }

  public function readoneAction($publication){
    $user = $this->container->get('security.context')->getToken()->getUser();
    $repository = $this->getDoctrine()->getManager()->getRepository('ResomaPublicationBundle:Publication');
    $publication = $repository->find($publication);
    $repository = $this->getDoctrine()->getManager()->getRepository('ResomaPublicationBundle:Approbation');
    $approbation = $repository->findBy(array('user' => $user, 'publication' => $publication));
    if(empty($approbation)){ $publication->setApprouve(0); }
    else { $publication->setApprouve($approbation[0]->getId()); }
    if($user == $publication->getAuteur()){ $self = true; }
    else{ $self = false; }
    $publication = $this->traitementPublication($publication, $user, $self);
    return $this->render('ResomaPublicationBundle:Publication:readone.html.twig', array('publication' => $publication, 'self' => $self));
  }

  public function readLastAction($user){
    $self = false;
    $repository = $this->getDoctrine()->getManager()->getRepository('ResomaPublicationBundle:Publication');
    $publications = $repository->findBy(array('auteur' => $user));
    $publi = array();
    foreach($publications as &$publication){
      //$publication->setDatePubli(date_format($publication->getDatePubli(), 'd/m/Y'));
      if($publication->getPublic()){
        $publication = $this->traitementPublication($publication, $user, $self);
        $publi[0] = $publication;
      }      
    } 
    return $this->render('ResomaPublicationBundle:Publication:read.html.twig', array('publications' => $publi, 'self' => $self, 'user' => $user));
  }

  public function addAction(){
    $publication = new Publication();
    $form = $this->createForm(new PublicationType, $publication);
    $request = $this->get('request');
    if($request->getMethod() == 'POST'){
      $form->bind($request);
      if($form->isValid()){
        $em = $this->getDoctrine()->getManager();              
        $id_auteur = $this->container->get('security.context')->getToken()->getUser();
        $publication->setAuteur($id_auteur);
        if($publication->getCategorie()->getId() == 3){
          $lien = explode('?v=', $publication->getLien());
          if(isset($lien[1])){
            $publication->setLien($lien[1]);
          }
          else{
            $repository = $this->getDoctrine()->getManager()->getRepository('ResomaPublicationBundle:Categorie');
            $categorie = $repository->find(1);
            $publication->setCategorie($categorie);
          }          
        }       
        $publication->setDatePubli(new \DateTime()); 
        $publication->setPublic(true);       
        $em->persist($publication);
        $em->flush();
        $liste = $_POST['liste'];
        $liste = trim($liste);        
        if($liste != ''){
          $listes = explode(';', $liste);
          foreach ($listes as &$liste) {
            $liste = preg_replace('#[^A-Za-z0-9]+#', '', $liste);
          }
          $listeur = $this->container->get('resoma_publication.listeur');          
          $listeur->listage($listes, $publication);
        } 
      }
      return $this->redirect($this->generateUrl('resoma_profil_index'));
    } 
    else{    
      return $this->render('ResomaPublicationBundle:Publication:add.html.twig', array('form' => $form->createView(), 'publication' => $publication)); 
    }
  }  

  public function updateAction($publication){
      $em = $this->getDoctrine()->getManager();
      $publications = $em->getRepository('ResomaPublicationBundle:Publication')->find($publication);
      $form = $this->createForm(new PublicationType, $publications);
      $request = $this->get('request'); 
      if($request->getMethod() == 'POST'){
        $form->bind($request);  
        $em->persist($publications);
        $em->flush();
        return $this->redirect($this->generateUrl('resoma_publication_readone', array('publication' => $publication)));
      }
      return $this->render('ResomaPublicationBundle:Publication:add.html.twig', array('publications' => $publications, 'form' => $form->createView()));
    }

    public function deleteAction(){
      $user = $this->container->get('security.context')->getToken()->getUser();
      $request = $this->container->get('request');
      if($request->isXmlHttpRequest()){
        $publication = (int)$request->request->get('publication');        
        $em = $this->getDoctrine()->getManager();
        $publication = $em->getRepository('ResomaPublicationBundle:Publication')->find($publication);
        if($user->getId() == $publication->getAuteur()->getId() || $this->container->get('security.context')->isGranted('ROLE_ADMIN')){
          $commentaires = $em->getRepository('ResomaPublicationBundle:Commentaire')->findBy(array('publication' => $publication));
          $repository = $this->getDoctrine()->getManager()->getRepository('ResomaPublicationBundle:Listage');
          $listage = $repository->findOneBy(array('publication' => $publication->getId()));
          if($listage != null){
            $liste = $listage->getListe();
            $em->remove($listage);
            $em->flush();
          }
          foreach($commentaires as $commentaire){
            $em->remove($commentaire);
          }
          $em->flush();
          $em->remove($publication);
          $em->flush();
          if(isset($liste)){
            $listages = $repository->findBy(array('liste' => $liste->getId()));
            if(empty($listages)){            
              $em->remove($liste); 
              $em->flush();
            }
          }
          return $this->container->get('templating')->renderResponse('ResomaSiteBundle:Site:index.html.twig');
        }        
      }
    }

    public function traitementPublication($publication, $user2, $self){
      $repository2 = $this->getDoctrine()->getManager()->getRepository('ResomaProfilBundle:Abonnement');
      $publication->urllink();
      $repository = $this->getDoctrine()->getManager()->getRepository('ResomaPublicationBundle:Commentaire');
      $publication->calculCommentaire($repository);
      $repository = $this->getDoctrine()->getManager()->getRepository('ResomaPublicationBundle:Approbation');
      $publication->calculScore($repository);
      $repository3 = $this->getDoctrine()->getManager()->getRepository('ResomaPublicationBundle:Listage');
      $approbation = $repository->findOneBy(array('user' => $user2, 'publication' => $publication));
      //$listage = $repository3->findOneBy(array('publication' => $publication));
      $listages = $repository3->findBy(array('publication' => $publication));
      if($listages != null){
        foreach ($listages as $listage){
          $listes[] = $listage->getListe();
        }
        $publication->setListe($listes);
      }
      else{
        $publication->setListe('vide');
      }
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
      // if(!is_string($publication->getDatePubli())){
      //   $publication->setDatePubli(date_format($publication->getDatePubli(), 'd/m/Y'));
      // }      
      $em = $this->getDoctrine()->getManager();
      $parametre = $em->getRepository('ResomaProfilBundle:Parametre')->findOneBy(array('user' => $publication->getAuteur()->getId()));
      if(empty($parametre)){
        $publication->getAuteur()->setAvatar('avatar/defaut.jpg');
      }
      else{
        if($parametre->getAvatar() == ''){
          $publication->getAuteur()->setAvatar('avatar/defaut.jpg');
        }
        else{
          $publication->getAuteur()->setAvatar('avatar/'.$publication->getAuteur()->getId().'/'.$parametre->getAvatar());
        }
      }
      return $publication;
    }

    public function feedAction(){
        $publications = $this->getDoctrine()->getRepository('ResomaPublicationBundle:Publication')->findAll();
        $feed = $this->get('eko_feed.feed.manager')->get('publication');
        $feed->addFromArray($publications);
        return new Response($feed->render('rss'));
    }
}

?>
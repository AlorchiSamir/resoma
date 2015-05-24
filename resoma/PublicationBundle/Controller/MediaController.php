<?php

namespace Resoma\PublicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Resoma\PublicationBundle\Entity\Publication;
use Resoma\PublicationBundle\Form\PublicationType;
use Resoma\PublicationBundle\Entity\Media;
use Resoma\PublicationBundle\Form\MediaType;

class MediaController extends Controller
{

  public function readAction(){
    $user = $this->container->get('security.context')->getToken()->getUser();
    $repository = $this->getDoctrine()->getManager()->getRepository('ResomaPublicationBundle:Media');
    $request = $this->container->get('request');
    if($request->isXmlHttpRequest()){
      $type = $request->request->get('type');
    }
    $medias = $repository->findBy(array('type' => $type, 'auteur' => $user));
    foreach($medias as &$media){
      $media->setSource('uploads/'.$media->getType().'/'.$media->getSource());
    }
    return $this->render('ResomaPublicationBundle:Media:read.html.twig', array('medias' => $medias, 'type' => $type));
  }

  public function addAction(){
    $media = new Media();
    $form = $this->createForm(new MediaType, $media);
    $request = $this->get('request');
    if($request->getMethod() == 'POST'){
      $form->bind($request);
      if ($form->isValid()){ 
        $mt = $media->getFile()->getClientMimeType();
        if($mt == 'image/gif' || $mt == 'image/png' || $mt == 'image/jpeg'){
          $media->setType('image');
        }
        else if($mt == 'audio/mp3'){
          $media->setType('audio');
        }
        else{
          $media->setType('divers');
        }     
        $id_auteur = $this->container->get('security.context')->getToken()->getUser();
        $media->setAuteur($id_auteur);
        $media->setDatePubli(new \DateTime());   
        $media->setPublic(true);     
        $media->upload($media->getType());
        $em = $this->getDoctrine()->getManager();
        $em->persist($media);
        $em->flush();
      }
      return $this->redirect($this->generateUrl('resoma_profil_index'));
    }       
    return $this->render('ResomaPublicationBundle:Media:add.html.twig', array('form' => $form->createView(), 'media' => $media)); 
  }  

  // public function updateAction($publication){
  //     $em = $this->getDoctrine()->getManager();
  //     $publications = $em->getRepository('ResomaPublicationBundle:Publication')->find($publication);
  //     $form = $this->createForm(new PublicationType, $publications);
  //     $request = $this->get('request'); 
  //     if($request->getMethod() == 'POST'){
  //       $form->bind($request);  
  //       $em->persist($publications);
  //       $em->flush();
  //       return $this->redirect($this->generateUrl('resoma_profil_index'));
  //     }
  //     return $this->render('ResomaPublicationBundle:Publication:add.html.twig', array('publications' => $publications, 'form' => $form->createView()));
  //   }

    public function deleteAction($image){
      $em = $this->getDoctrine()->getManager();
      $publication = $em->getRepository('ResomaPublicationBundle:Image')->find($image);
      $em->flush();
      $em->remove($image);
      $em->flush();
      return $this->redirect($this->generateUrl('resoma_profil_index'));
    }
}

?>
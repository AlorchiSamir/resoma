<?php

namespace Resoma\PublicationBundle\Listeur;

use Resoma\PublicationBundle\Entity\Liste;
use Resoma\PublicationBundle\Entity\Listage;
use Symfony\Bundle\DoctrineBundle\Registry;

class Listeur
{

  /**
  * @var Symfony\Bundle\DoctrineBundle\Registry
  */
  protected $doctrine;

  public function __construct($doctrine)
  {      
    $this->doctrine = $doctrine;
  }

  /**
   * Enregistre une notification
   *
   * @param \Resoma\PublicationBundle\Entity\Liste $liste
   * @return null
   */
  public function listage($listes, $publication){
    $repository = $this->doctrine->getManager()->getRepository('ResomaPublicationBundle:Liste');
    foreach ($listes as &$nom) {    
      $liste = $repository->findOneByNom($nom);
      if($liste == null){
        $liste = new Liste();
        $liste->setNom($nom);
        $liste->setDateCreation(new \DateTime());
        $em = $this->doctrine->getManager();
        $em->persist($liste);
        $em->flush();
        $liste = $repository->findOneByNom($nom);
      }
      $listage = new Listage(); 
      $listage->setListe($liste);
      $listage->setPublication($publication);
      $listage->setDateListage(new \DateTime());
      $em = $this->doctrine->getManager();
      $em->persist($listage);
      $em->flush();
    }
  } 
}
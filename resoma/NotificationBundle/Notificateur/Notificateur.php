<?php

namespace Resoma\NotificationBundle\Notificateur;

use Resoma\NotificationBundle\Entity\Notification;
use Symfony\Bundle\DoctrineBundle\Registry;

class Notificateur
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
   * @param \Resoma\UserBundle\Entity\User $user
   * @return null
   */
  public function addNotif($user, $texte){
      $notification = new Notification();
      $notification->setDateNotif(new \DateTime());
      $notification->setVu(false);
      $notification->setTexte($texte);
      $notification->setUser($user);
      $em = $this->doctrine->getManager();
      $em->persist($notification);
      $em->flush();
  } 

  /**
   * Enregistre une notification
   *
   * @param \Resoma\UserBundle\Entity\User $user
   * @return null
   */

  public function getNombre($user){
    $repository = $this->doctrine->getManager()->getRepository('ResomaNotificationBundle:Notification');
    $notifications = $repository->findBy(array('user' => $user, 'vu' => false));
    $cpt = 0;
    foreach($notifications as &$notification){
      $cpt++;
    }
    return $cpt;
  }
}
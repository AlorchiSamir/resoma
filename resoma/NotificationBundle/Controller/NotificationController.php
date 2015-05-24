<?php

namespace Resoma\NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NotificationController extends Controller
{
    public function readAction(){
    	$em = $this->getDoctrine()->getManager();
    	$user = $this->container->get('security.context')->getToken()->getUser();
        $repository = $this->getDoctrine()->getManager()->getRepository('ResomaProfilBundle:Parametre');
        $parametre = $repository->findOneBy(array('user' => $user));
        if(!empty($parametre) && $parametre->getDisabled() == true){            
            return $this->render('ResomaSiteBundle:Site:disabled.html.twig');      
        }
        else{
        	$repository = $em->getRepository('ResomaNotificationBundle:Notification');
        	$notifications = $repository->findBy(array('user' => $user, 'vu' => false), array('dateNotif' => 'desc'));
        	foreach ($notifications as $notification){
            	$notification->setVu(true);
            	$em->persist($notification);
            	$em->flush();
          	}
        	return $this->render('ResomaNotificationBundle:Notification:layout.html.twig', array('notifications' => $notifications));
        }
  	} 
}
<?php
// src/Alorchi\FormationBundle/DataFixtures/ORM/Formations.php

namespace Resoma\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Resoma\UserBundle\Entity\User;

class Users implements FixtureInterface
{
  // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
  public function load(ObjectManager $manager)
  {
    // Liste des noms de catégorie à ajouter
    $users[0] = array('userName' => 'Babos', 'password' => 'samir', 'nom' => 'Alorchi', 'prenom' => 'Samir', 'mail' => 'samir.alorchi@gmail.com');
    $users[1] = array('userName' => 'Jean', 'password' => 'jean', 'nom' => 'Dupont', 'prenom' => 'Jean', 'mail' => 'jean.dupont@gmail.com');

    foreach($users as $i => $user)
    {
      // On crée la catégorie
      $liste_users[$i] = new User();
      $liste_users[$i]->setUserName($user['userName']);
      $liste_users[$i]->setPassword($user['password']);
      $liste_users[$i]->setNom($user['nom']);
      $liste_users[$i]->setPrenom($user['prenom']);
      $liste_users[$i]->setEmail($user['mail']);
       // On la persiste
      $manager->persist($liste_users[$i]);
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }
}
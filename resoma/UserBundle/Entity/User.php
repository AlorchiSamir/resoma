<?php
// src/Alorchi/UserBundle/Entity/User.php

namespace Resoma\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Resoma\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @var string
   *
   * @ORM\Column(name="nom", type="string", length=255)
   */
  protected $nom;

  /**
   * @var string
   *
   * @ORM\Column(name="prenom", type="string", length=255)
   */
  protected $prenom;

  /**
   * @var string
   *
   * @ORM\Column(name="avatar", type="string", length=255)
   */
  protected $avatar;

  /**
    * @var boolean
    *
    * @ORM\Column(name="tuto", type="boolean", nullable=true, options={"default":0})
    */
  private $tuto;

  protected $description;

  protected $derniereConnection;

     /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setDerniereConnexion($derniereConnection){
        $this->derniereConnection = $derniereConnection;
        return $this;
    }

    public function getDerniereConnexion(){
        return $this->derniereConnection;
    }

    public function setDescription($description){
        $this->description = $description;
        return $this;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getIntervalle(){
      $now = new \DateTime();
      $interval = date_diff($this->getLastLogin(), $now);
      if($interval->y > 0){
        return $interval->y.' année';
      }
      if($interval->m > 0){
        return $interval->m.' mois';
      }
      if($interval->d > 0){
        return $interval->d.' jour';
      }
      if($interval->h > 0){
        return $interval->h.' heure';
      }
      if($interval->i > 0){
        return $interval->i.' minute';
      }
      if($interval->s > 0){
        return $interval->s.' seconde';
      }
    }    

    /**
     * Set tuto
     *
     * @param boolean $tuto
     * @return User
     */
    public function setTuto($tuto)
    {
        $this->tuto = $tuto;

        return $this;
    }

    /**
     * Get tuto
     *
     * @return boolean 
     */
    public function getTuto()
    {
        return $this->tuto;
    }
}

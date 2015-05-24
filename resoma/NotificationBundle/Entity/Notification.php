<?php

namespace Resoma\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Resoma\NotificationBundle\Entity\NotificationRepository")
 */
class Notification
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @ORM\ManyToOne(targetEntity="Resoma\UserBundle\Entity\User")
    * @ORM\JoinColumn(nullable=false)
    */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNotif", type="datetime")
     */
    private $dateNotif;

    /**
     * @var boolean
     *
     * @ORM\Column(name="vu", type="boolean")
     */
    private $vu;

    /**
     * @var string
     *
     * @ORM\Column(name="texte", type="text")
     */
    private $texte;


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
     * Set dateNotif
     *
     * @param \DateTime $dateNotif
     * @return Notification
     */
    public function setDateNotif($dateNotif)
    {
        $this->dateNotif = $dateNotif;

        return $this;
    }

    /**
     * Get dateNotif
     *
     * @return \DateTime 
     */
    public function getDateNotif()
    {
        return $this->dateNotif;
    }

    /**
     * Set vu
     *
     * @param boolean $vu
     * @return Notification
     */
    public function setVu($vu)
    {
        $this->vu = $vu;

        return $this;
    }

    /**
     * Get vu
     *
     * @return boolean 
     */
    public function getVu()
    {
        return $this->vu;
    }

    /**
     * Set texte
     *
     * @param string $texte
     * @return Notification
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get texte
     *
     * @return string 
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * Set user
     *
     * @param \Resoma\UserBundle\Entity\User $user
     * @return Notification
     */
    public function setUser(\Resoma\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Resoma\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}

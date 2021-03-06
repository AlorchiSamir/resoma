<?php

namespace Resoma\ProfilBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Abonnement
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Resoma\ProfilBundle\Entity\AbonnementRepository")
 */
class Abonnement
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
    private $user1;

    /**
    * @ORM\ManyToOne(targetEntity="Resoma\UserBundle\Entity\User")
    * @ORM\JoinColumn(nullable=false)
    */
    private $user2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ajout", type="datetime")
     */
    private $ajout;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ajoutAmi", type="datetime", nullable=true)
     */
    private $ajoutAmi;

    /**
     * @var \boolean
     *
     * @ORM\Column(name="ami", type="boolean")
     */
    private $ami;


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
     * Set ajout
     *
     * @param \DateTime $ajout
     * @return Abonnement
     */
    public function setAjout($ajout)
    {
        $this->ajout = $ajout;

        return $this;
    }

    /**
     * Get ajout
     *
     * @return \DateTime 
     */
    public function getAjout()
    {
        return $this->ajout;
    }

    /**
     * Set user1
     *
     * @param \Resoma\UserBundle\Entity\User $user1
     * @return Abonnement
     */
    public function setUser1(\Resoma\UserBundle\Entity\User $user1)
    {
        $this->user1 = $user1;

        return $this;
    }

    /**
     * Get user1
     *
     * @return \Resoma\UserBundle\Entity\User 
     */
    public function getUser1()
    {
        return $this->user1;
    }

    /**
     * Set user2
     *
     * @param \Resoma\UserBundle\Entity\User $user2
     * @return Abonnement
     */
    public function setUser2(\Resoma\UserBundle\Entity\User $user2)
    {
        $this->user2 = $user2;

        return $this;
    }

    /**
     * Get user2
     *
     * @return \Resoma\UserBundle\Entity\User 
     */
    public function getUser2()
    {
        return $this->user2;
    }

    /**
     * Set ajoutAmi
     *
     * @param \DateTime $ajoutAmi
     * @return Abonnement
     */
    public function setAjoutAmi($ajoutAmi)
    {
        $this->ajoutAmi = $ajoutAmi;

        return $this;
    }

    /**
     * Get ajoutAmi
     *
     * @return \DateTime 
     */
    public function getAjoutAmi()
    {
        return $this->ajoutAmi;
    }

    /**
     * Set ami
     *
     * @param boolean $ami
     * @return Abonnement
     */
    public function setAmi($ami)
    {
        $this->ami = $ami;

        return $this;
    }

    /**
     * Get ami
     *
     * @return boolean 
     */
    public function getAmi()
    {
        return $this->ami;
    }
}

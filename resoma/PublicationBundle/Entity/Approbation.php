<?php

namespace Resoma\PublicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Approbation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Resoma\PublicationBundle\Entity\ApprobationRepository")
 */
class Approbation
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
    * @ORM\ManyToOne(targetEntity="Resoma\PublicationBundle\Entity\Publication")
    * @ORM\JoinColumn(nullable=false)
    */
    private $publication;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAppro", type="datetime")
     */
    private $dateAppro;


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
     * Set dateAppro
     *
     * @param \DateTime $dateAppro
     * @return Approbation
     */
    public function setDateAppro($dateAppro)
    {
        $this->dateAppro = $dateAppro;

        return $this;
    }

    /**
     * Get dateAppro
     *
     * @return \DateTime 
     */
    public function getDateAppro()
    {
        return $this->dateAppro;
    }

    /**
     * Set user
     *
     * @param \Resoma\UserBundle\Entity\User $user
     * @return Approbation
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

    /**
     * Set publication
     *
     * @param \Resoma\PublicationBundle\Entity\Publication $publication
     * @return Approbation
     */
    public function setPublication(\Resoma\PublicationBundle\Entity\Publication $publication)
    {
        $this->publication = $publication;

        return $this;
    }

    /**
     * Get publication
     *
     * @return \Resoma\PublicationBundle\Entity\Publication 
     */
    public function getPublication()
    {
        return $this->publication;
    }
}

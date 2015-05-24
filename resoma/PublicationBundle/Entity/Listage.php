<?php

namespace Resoma\PublicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Listage
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Resoma\PublicationBundle\Entity\ListageRepository")
 */
class Listage
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
    * @ORM\ManyToOne(targetEntity="Resoma\PublicationBundle\Entity\Liste")
    * @ORM\JoinColumn(nullable=false)
    */
    private $liste;

    /**
    * @ORM\ManyToOne(targetEntity="Resoma\PublicationBundle\Entity\Publication")
    * @ORM\JoinColumn(nullable=false)
    */
    private $publication;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateListage", type="datetime")
     */
    private $dateListage;


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
     * Set dateListage
     *
     * @param \DateTime $dateListage
     * @return Listage
     */
    public function setDateListage($dateListage)
    {
        $this->dateListage = $dateListage;

        return $this;
    }

    /**
     * Get dateListage
     *
     * @return \DateTime 
     */
    public function getDateListage()
    {
        return $this->dateListage;
    }

    /**
     * Set liste
     *
     * @param \Resoma\PublicationBundle\Entity\Liste $liste
     * @return Listage
     */
    public function setListe(\Resoma\PublicationBundle\Entity\Liste $liste)
    {
        $this->liste = $liste;

        return $this;
    }

    /**
     * Get liste
     *
     * @return \Resoma\PublicationBundle\Entity\Liste 
     */
    public function getListe()
    {
        return $this->liste;
    }

    /**
     * Set publication
     *
     * @param \Resoma\PublicationBundle\Entity\Publication $publication
     * @return Listage
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

<?php

namespace Resoma\PublicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Liste
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Resoma\PublicationBundle\Entity\ListeRepository")
 */
class Liste
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime")
     */
    private $dateCreation;

    private $nombrePubli;


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
     * @return Liste
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Liste
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    public function setNombrePubli($nombrePubli){
        $this->nombrePubli = $nombrePubli;
        return $this;
    }

    public function getNombrePubli(){
        return $this->nombrePubli;
    }

    public function calculPubli($repository){
        $cpt = 0;
        $listages = $repository->findBy(array('liste' => $this));
        foreach($listages as &$listage){
            $cpt++;
        }
        $this->setNombrePubli($cpt);
    }
}

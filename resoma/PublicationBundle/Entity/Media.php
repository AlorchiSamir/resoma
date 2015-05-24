<?php

namespace Resoma\PublicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Media
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Resoma\PublicationBundle\Entity\MediaRepository")
 */
class Media
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
    private $auteur;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255)
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=100)
     */
    private $titre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePubli", type="datetime")
     */
    private $datePubli;

    /**
     * @var boolean
     *
     * @ORM\Column(name="public", type="boolean")
     */
    private $public;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=50)
     */
    private $type;

    private $file;

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
     * Set source
     *
     * @param string $source
     * @return Media
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Media
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set datePubli
     *
     * @param \DateTime $datePubli
     * @return Media
     */
    public function setDatePubli($datePubli)
    {
        $this->datePubli = $datePubli;

        return $this;
    }

    /**
     * Get datePubli
     *
     * @return \DateTime 
     */
    public function getDatePubli()
    {
        return $this->datePubli;
    }

    /**
     * Set public
     *
     * @param boolean $public
     * @return Media
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean 
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Media
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set auteur
     *
     * @param \Resoma\UserBundle\Entity\User $auteur
     * @return Media
     */
    public function setAuteur(\Resoma\UserBundle\Entity\User $auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return \Resoma\UserBundle\Entity\User 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    public function setFile($file){
        $this->file = $file;
        return $this;
    }

    public function getFile(){
        return $this->file;
    }

    public function upload($folder)
    {
        if (null === $this->file) {
          return;
        }
        //$taille = getImageSize($this->file);
        $name = $this->file->getClientOriginalName();
        $this->file->move($this->getUploadRootDir($folder), $name);
        $this->source = $name;
    }

    public function getUploadDir($folder)
    {
      // On retourne le chemin relatif vers l'image pour un navigateur
      return 'uploads/'.$folder;
    }

    protected function getUploadRootDir($folder)
    {
      // On retourne le chemin relatif vers l'image pour notre code PHP
      return __DIR__.'/../../../../web/'.$this->getUploadDir($folder);
    }
}

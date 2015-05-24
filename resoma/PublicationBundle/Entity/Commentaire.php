<?php

namespace Resoma\PublicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaire
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Resoma\PublicationBundle\Entity\CommentaireRepository")
 */
class Commentaire
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
    * @ORM\ManyToOne(targetEntity="Resoma\PublicationBundle\Entity\Publication")
    * @ORM\JoinColumn(nullable=true)
    */
    private $publication;

    /**
    * @ORM\ManyToOne(targetEntity="Resoma\PublicationBundle\Entity\Commentaire")
    * @ORM\JoinColumn(nullable=true)
    */
    private $commentaire;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePubli", type="datetime")
     */
    private $datePubli;

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
     * Set texte
     *
     * @param string $texte
     * @return Commentaire
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
     * Set datePubli
     *
     * @param \DateTime $datePubli
     * @return Commentaire
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
     * Set auteur
     *
     * @param \Resoma\UserBundle\Entity\User $auteur
     * @return Commentaire
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

    /**
     * Set publication
     *
     * @param \Resoma\PublicationBundle\Entity\Publication $publication
     * @return Commentaire
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

    /**
     * Set commentaire
     *
     * @param \Resoma\PublicationBundle\Entity\Commentaire $commentaire
     * @return Commentaire
     */
    public function setCommentaire(\Resoma\PublicationBundle\Entity\Commentaire $commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return \Resoma\PublicationBundle\Entity\Commentaire 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    public function urllink(){
        $content = $this->getTexte();
        $content = strip_tags($content);
        $content = preg_replace('#(((https?://)|(w{3}\.))+[a-zA-Z0-9&;\#\.\?=_/-]+\.([a-z]{2,4})([a-zA-Z0-9&;\#\.\?=_/-]+))#i', '<a href="$0" target="_blank">$0</a>', $content);
        // Si on capte un lien tel que www.test.com, il faut rajouter le http://
        if(preg_match('#<a href="www\.(.+)" target="_blank">(.+)<\/a>#i', $content)) {
            $content = preg_replace('#<a href="www\.(.+)" target="_blank">(.+)<\/a>#i', '<a href="http://www.$1" target="_blank">www.$1</a>', $content);
        //preg_replace('#<a href="www\.(.+)">#i', '<a href="http://$0">$0</a>', $content);
        }
        $content = stripslashes($content);        
        $this->setTexte($content);
    }
}

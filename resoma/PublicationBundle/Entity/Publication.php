<?php

namespace Resoma\PublicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eko\FeedBundle\Item\Writer\RoutedItemInterface;

/**
 * Publication
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Resoma\PublicationBundle\Entity\PublicationRepository")
 */
class Publication implements RoutedItemInterface
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
     * @ORM\Column(name="titre", type="string", length=100)
     */
    private $titre;

    /**
    * @ORM\ManyToOne(targetEntity="Resoma\PublicationBundle\Entity\Categorie")
    * @ORM\JoinColumn(nullable=false)
    */
    private $categorie;

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
     * @var string
     *
     * @ORM\Column(name="lien", type="text")
     */
    private $lien;

    /**
     * @var boolean
     *
     * @ORM\Column(name="public", type="boolean")
     */
    private $public;

    private $approuve;

    private $publiable;

    private $liste;

    private $score;

    private $commentaire;

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
     * @return Publication
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
     * Set auteur
     *
     * @param \Resoma\UserBundle\Entity\User $auteur
     * @return Publication
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
     * Set datePubli
     *
     * @param \DateTime $datePubli
     * @return Publication
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
     * Set categorie
     *
     * @param \Resoma\PublicationBundle\Entity\Categorie $categorie
     * @return Publication
     */
    public function setCategorie(\Resoma\PublicationBundle\Entity\Categorie $categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \Resoma\PublicationBundle\Entity\Categorie 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set lien
     *
     * @param string $lien
     * @return Publication
     */
    public function setLien($lien)
    {
        $this->lien = $lien;

        return $this;
    }

    /**
     * Get lien
     *
     * @return string 
     */
    public function getLien()
    {
        return $this->lien;
    }

    /**
     * Set public
     *
     * @param boolean $public
     * @return Publication
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

    public function setApprouve($approuve){
        $this->approuve = $approuve;
        return $this;
    }

    public function getApprouve(){
        return $this->approuve;
    }

    public function setPubliable($publiable){
        $this->publiable = $publiable;
        return $this;
    }

    public function getPubliable(){
        return $this->publiable;
    }

    public function setListe($liste){
        $this->liste = $liste;
        return $this;
    }

    public function getListe(){
        return $this->liste;
    }

    public function setScore($score){
        $this->score = $score;
        return $this;
    }

    public function getScore(){
        return $this->score;
    }

    public function setCommentaire($commentaire){
        $this->commentaire = $commentaire;
        return $this;
    }

    public function getCommentaire(){
        return $this->commentaire;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Publication
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

    public function calculScore($repository){
        $cpt = 0;
        $approbations = $repository->findBy(array('publication' => $this));
        foreach($approbations as &$approbation){
            $cpt++;
        }
        $this->setScore($cpt);
    }

    public function calculCommentaire($repository){
        $cpt = 0;
        $commentaires = $repository->findBy(array('publication' => $this));
        foreach($commentaires as &$commentaire){
            $cpt++;
        }
        $this->setCommentaire($cpt);
    }

    public function getFeedItemTitle(){
        return $this->titre;
    }

    public function getFeedItemDescription(){
        return $this->texte;
    }

    public function getFeedItemPubDate(){
        return $this->datePubli;
    }

    public function getFeedItemRouteName(){
        return 'resoma_publication_readone';
    }

    public function getFeedItemRouteParameters(){
        return array('publication' => $this->id);
    }
    
    public function getFeedItemUrlAnchor(){

    }
}

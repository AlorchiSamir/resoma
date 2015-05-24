<?php

namespace Resoma\PublicationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ListeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ListeRepository extends EntityRepository
{
	public function findListe($nom){
		$query = $this->_em->createQuery('SELECT l FROM ResomaPublicationBundle:Liste l
															WHERE l.nom LIKE :nom');
		$query->setParameter('nom', '%'.$nom.'%');
	    return $query->getResult();
    }

    public function findListage(){
		$query = $this->_em->createQuery('SELECT l, COUNT(SELECT li FROM ResomaPublicationBundle:Listage li) as n 
											FROM ResomaPublicationBundle:Liste l ORDER BY n');
		return $query->getResult();
    }
}
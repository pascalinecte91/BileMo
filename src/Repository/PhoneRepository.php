<?php

namespace App\Repository;

use App\Entity\Phone;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


/**
 * @method Phone|null find($id, $lockMode = null, $lockVersion = null)
 * @method Phone|null findOneBy(array $criteria, array $orderBy = null)
 * @method Phone[]    findAll()
 * @method Phone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Phone::class);
    }
    //2 parametres, 2 valeurs dans la methode soit la page  et maximum 5 per page
    public function findAllPhones($page, $max)
    {
        if (!is_numeric($page)) {
            throw new InvalidArgumentException(
                'La valeur de l\'argument $page est incorrecte (valeur : ' . $page . ').'
            );
        }

        if ($page < 1) {
            throw new NotFoundHttpException('La page demandée n\'existe pas');
        }

        if (!is_numeric($max)) {
            throw new InvalidArgumentException(
                'La valeur de l\'argument $max est incorrecte (valeur : ' . $max . ').'
            );
        }
        $query = $this->createQueryBuilder('pg')   //genere ma requete  ma page

            ->setFirstResult(($page - 1) * $max)  // affectation du resultat  
            ->setMaxResults($max)  //  tu m'affectes 5 requetes tel max
            ->getQuery();                  // obtient  la page

        return new Paginator($query);
    }
}

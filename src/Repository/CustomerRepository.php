<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    /** 
     * @param int $id
     * @param integer $page
     * @param integer user_id
     * @param string $order
     * @param [type] $user
     * @return Customer
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     */
    public function findById(int $id): Customer
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if ($query instanceof Customer) {
            return $query;
        }

        throw new EntityNotFoundException("Client introuvable !");
    }

    public function findAllCustomers($page, $max)
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
            ->setMaxResults($max)  //  tu m'affectes nbre tel max
            ->getQuery();                  // obtient  la page

        return new Paginator($query);
    }
}

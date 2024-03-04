<?php

namespace App\Repository;

use App\Entity\Show;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Show>
 *
 * @method Show|null find($id, $lockMode = null, $lockVersion = null)
 * @method Show|null findOneBy(array $criteria, array $orderBy = null)
 * @method Show[]    findAll()
 * @method Show[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Show::class);
    }

   /**
    * @return Show[] Returns an array of Show objects
    */
   public function findByRatingOver(float $minRating, int $maxResults = 5, int $pageNumber = 1): array
   {

        // ici on peut écrire du SQL 
        // ou du DQL 
        // ou utiliser le QueryBuilder à découvrir
        $offset = $maxResults * ($pageNumber - 1);

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s
            FROM \App\Entity\Show AS s
            WHERE s.rating >= :min_rating
            ORDER BY s.title ASC
            '
        )->setParameter('min_rating', $minRating);

        $query->setMaxResults($maxResults);
        $query->setFirstResult($offset);

        // returns an array of Product objects
        return $query->getResult();
   }

   public function findOneWithCastingsAndPersons($showId): ?Show
   {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s, c, p, g, z
                FROM \App\Entity\Show AS s
                INNER JOIN s.castings AS c 
                INNER JOIN c.person AS p
                INNER JOIN s.genres AS g
                LEFT JOIN s.seasons AS z
                WHERE s.id = :show_id
                ORDER BY z.number ASC, c.creditOrder ASC
            '
        )->setParameter('show_id', $showId);

        return $query->getOneOrNullResult();
   }
//    public function findOneBySomeField($value): ?Show
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
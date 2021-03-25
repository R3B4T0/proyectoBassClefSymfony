<?php

namespace App\Repository;

use App\Entity\Conversacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Conversacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversacion[]    findAll()
 * @method Conversacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversacion::class);
    }

    private function findConversacionByParticipantes(int $otroUsuarioId, int $miId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->select($qb->expr()->count('p.conversacion'))
            ->innerJoin('c.participantes', 'p')
            ->where(
                $qb->expr()->eq('p.usuario', ':yo'),
                $qb->expr()->eq('p.usuario', ':otroUsuario')
            )
            ->groupBy('p.converacion')
            ->having(
                $qb->expr()->eq(
                    $qb->expr()->count('p.conversacion'), 2
                )
            )
            ->setParameters([
                'yo' => $miId,
                'otroUsuario' => $otroUsuarioId
            ]);
        
        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Conversacion[] Returns an array of Conversacion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Conversacion
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

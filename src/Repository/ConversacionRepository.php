<?php

namespace App\Repository;

use App\Entity\Conversacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
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

    public function findConversacionByParticipantes(?int $otroUsuarioId, $miId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->select($qb->expr()->count('p.conversacion'))
            ->innerJoin('c.participantes', 'p')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('p.usuario', ':yo'),
                    $qb->expr()->eq('p.usuario', ':otroUsuario')
                )
            )
            ->groupBy('p.conversacion')
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

    public function findConversacionesByUsuario (int $usuarioId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->
            select('otroUsuario.nombre', 'c.id as conversacionId', 'um.contenido', 'um.creadoEl')
            ->innerJoin('c.participantes', 'p', Join::WITH, $qb->expr()->neq('p.usuario', ':usuario'))
            ->innerJoin('c.participantes', 'yo', Join::WITH, $qb->expr()->eq('yo.usuario', ':usuario'))
            ->leftJoin('c.ultimoMensaje', 'um')
            ->innerJoin('yo.usuario', 'yoUsuario')
            ->innerJoin('p.usuario', 'otroUsuario')
            ->where('yoUsuario.id = :usuario')
            ->setParameter('usuario', $usuarioId)
            ->orderBy('um.creadoEl', 'DESC')
        ;

        return $qb->getQuery()->getResult();
    }

    public function checkIfUsuarioEsParticipante(int $conversacionId, int $usuarioId)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->innerJoin('c.participantes', 'p')
            ->where('c.id = :conversacionId')
            ->andWhere(
                $qb->expr()->eq('p.usuario', ':usuarioId')
            )
            ->setParameters([
                'conversacionId' => $conversacionId,
                'usuarioId' => $usuarioId
            ])
        ;

        return $qb->getQuery()->getOneOrNullResult();
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

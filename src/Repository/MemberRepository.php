<?php
// src/Repository/MemberRepository.php

namespace App\Repository;

use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Member>
 */
class MemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Member::class);
    }

    /* Récupère toutes les activités avec leurs sessions et niveaux pour un membre spécifique. */
    public function findAllActivitiesWithSessionsAndLevelsForMember(Member $member): array
    {
        $qb = $this->createQueryBuilder('m')
            ->join('m.activities', 'a')
            ->join('a.sessions', 's')
            ->join('a.level', 'l')
            ->where('m.id = :memberId')
            ->setParameter('memberId', $member->getId())
            ->select(
                'a.id AS id',
                'a.label AS activity_name',
                's.date AS session_date',
                's.heure AS session_time',
                's.duration AS session_duration',
                'l.label AS level_label'
            )
            ->orderBy('s.date', 'ASC')
            ->addOrderBy('a.label', 'ASC');

        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function findAllActivities(): array
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->getQuery()
            ->getResult();
    }
}

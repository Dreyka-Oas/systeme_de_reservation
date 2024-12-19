<?php
// src/Repository/ActivityRepository.php

namespace App\Repository;

use App\Entity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Activity>
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    /* Récupère toutes les activités avec leurs sessions et niveaux, sans les dates.*/
    public function findAllActivitiesWithSessionsAndLevels(): array
    {
        $qb = $this->createQueryBuilder('a')
            ->join('a.sessions', 's')
            ->join('a.level', 'l')
            ->select(
                'a.id AS id',
                'a.label AS activity_name',
                'l.label AS level_label',
                's.date AS session_date',
                's.heure AS session_time',
                's.duration AS session_duration'
            )
            ->orderBy('s.date', 'ASC')
            ->addOrderBy('a.label', 'ASC');

        $query = $qb->getQuery();

        return $query->getResult();
    }
}
// SELECT
//     a.id AS id,
//     a.label AS activity_name,
//     l.label AS level_label,
//     s.date AS session_date,
//     s.heure AS session_time,
//     s.duration AS session_duration
// FROM
//     activity a
// JOIN
//     session s ON a.id = s.activity_id
// JOIN
//     level l ON a.level_id = l.id
// ORDER BY
//     s.date ASC, a.label ASC;

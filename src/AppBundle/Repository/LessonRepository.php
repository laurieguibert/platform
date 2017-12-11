<?php

namespace AppBundle\Repository;

/**
 * LessonRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LessonRepository extends \Doctrine\ORM\EntityRepository
{
    public function getByDurationAndDurationType($duration, $type){
        $qb = $this->createQueryBuilder('l')
            ->where('l.duration = :duration')
            ->andWhere('l.duration_type = :durationType')
            ->setParameter('duration', $duration)
            ->setParameter('durationType', $type)
            ->getQuery();

        return $qb->execute();
    }
}

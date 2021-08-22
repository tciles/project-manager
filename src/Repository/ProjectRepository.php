<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findProjectsForVersionsSynchronisation(): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb->where('p.active = TRUE')
            ->andWhere('p.fullname IS NOT NULL');

        return $qb->getQuery()->getResult();
    }

    public function persist(Project $project): void
    {
        $this->getEntityManager()->persist($project);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}

<?php

namespace App\Repository;

use App\Entity\ProjectVersion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProjectVersionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectVersion::class);
    }

    public function persist(ProjectVersion $project): void
    {
        $this->getEntityManager()->persist($project);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}

<?php

namespace App\Adapter;

use App\Entity\Project;

interface ApiAdapterInterface
{
    public function getRepository(Project $project): array;
    public function getReleases(Project $project): array;
}

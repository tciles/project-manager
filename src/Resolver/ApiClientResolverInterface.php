<?php

namespace App\Resolver;

use App\Adapter\ApiAdapterInterface;

interface ApiClientResolverInterface
{
    public function resolve(?string $url = null): ApiAdapterInterface;
}

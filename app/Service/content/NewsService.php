<?php

namespace App\Service\content;

use App\Service\IService;
use App\Repository\content\NewsRepository as Repository;



class NewsService extends IService
{
    public function __construct(
        protected readonly Repository $repository
    ) {}
}

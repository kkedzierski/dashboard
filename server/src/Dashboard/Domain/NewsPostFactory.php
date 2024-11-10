<?php

namespace App\Dashboard\Domain;

use App\Dashboard\Ui\NewsPostDto;

class NewsPostFactory
{
    public function create(NewsPostDto $newsPostDto): NewsPost
    {
        return new NewsPost($newsPostDto->title, $newsPostDto->content);
    }
}

<?php

namespace App\EventListener;

use App\Entity\Movie;
use App\Service\MySlugger;

class MovieListener
{
    private $slugger;

    public function __construct(MySlugger $slugger)
    {
        $this->slugger = $slugger;    
    }

    public function updateSlug(Movie $movie)
    {
        $slug = $this->slugger->sluggify($movie->getTitle());
        $movie->setSlug($slug);
    }
    
}
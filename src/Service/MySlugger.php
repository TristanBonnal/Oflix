<?php
namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;

class MySlugger
{
    private $slugger;

    public function __construct(SluggerInterface $slugger, $toLower) 
    {
        $this->slugger =$slugger;
        $this->toLower = $toLower;
    }

    /**
     * Fonction permettant de produire un slug personnalisé
     *
     * @param string $words
     * @return string
     */
    public function sluggify(string $words): string
    {
        return $this->toLower ? $this->slugger->slug($words)->lower() : $this->slugger->slug($words);
    }
}
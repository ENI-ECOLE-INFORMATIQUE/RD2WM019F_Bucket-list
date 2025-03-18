<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = ['Travel & Adventure', 'Sport', 'Entertainment', 'Human Relations', 'Others', 'Celle de plus'];

        foreach ($categories as $name){

            $category = new Category();
            $category->setName($name);

            $manager->persist($category);
        }
        $manager->flush();
    }
}

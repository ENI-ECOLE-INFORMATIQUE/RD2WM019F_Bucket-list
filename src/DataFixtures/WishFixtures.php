<?php

namespace App\DataFixtures;

use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WishFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        for($i = 0; $i < 10; $i++) {
            $wish = new Wish();
            $wish->setTitle($faker->words(2, true));
            $wish->setDescription($faker->sentences(2, true));
            $wish->setAuthor($faker->name);
            $wish->setIsPublished($faker->boolean);
            $wish->setDateCreated(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-6 months','now')));

            $manager->persist($wish);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}

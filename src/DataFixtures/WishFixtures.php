<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WishFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //permet de récupérer le repository d'une entité afin de faire une requête
        $categories = $manager->getRepository(Category::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();

        $faker = \Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 20; $i++) {
            $wish = new Wish();
            $wish->setTitle($faker->words(2, true));
            $wish->setDescription($faker->sentences(2, true));
            $wish->setAuthor($faker->name);
            $wish->setUser($faker->randomElement($users));
            $wish->setIsPublished($faker->boolean);
            $wish->setCategory($faker->randomElement($categories));
            $wish->setDateCreated(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-6 months', 'now')));

            $manager->persist($wish);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class
        ];
    }
}

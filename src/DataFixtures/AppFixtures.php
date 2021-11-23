<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Page;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private $hasher;
    private $faker;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user   ->setEmail('fiquet.noah@gmail.com')
                ->setRoles(["ROLE_ADMIN"])
                ->setCreatedAt(new \DateTimeImmutable())
                ->setIsVerified(true);
        $password = $this->hasher->hashPassword($user,'123456789');
        $user->setPassword($password);

        $manager->persist($user);

        $page = new Page();
        $page           ->setName("Accueil")
                        ->setSlug("accueil")
                        ->setContent("Hello World")
                        ->setStatus("Publique")
                        ->setPublicatedAt(new \DateTime())
                        ;
        $manager->persist($page);

        for($i=0;$i<5;$i++){
            $page = new Page();
            $page           ->setName($this->faker->word())
                            ->setSlug($this->faker->slug())
                            ->setContent($this->faker->text())
                            ->setStatus("Publique")
                            ->setPublicatedAt(new \DateTime())
                            ;
            $manager->persist($page);
        }

        $categories = [];
        for($i=0;$i<10;$i++){
            $category = new Category();
            $category   ->setName($this->faker->word())
                        ->setColor($this->faker->hexColor())
                        ;
            $manager->persist($category);
            $categories[] = $category;
        }
            
        for ($i=0; $i < 100; $i++){
            $article = new Article();
            $article    ->setName($this->faker->word())
                        ->setSlug($this->faker->slug())
                        ->setContent($this->faker->text())
                        ->setStatus('Publique')
                        ->setCreatedAt(new DateTimeImmutable($this->generateDateTime()))
                        ->setPublicatedAt(new \DateTime($this->generateDateTime()))
                        ->addCategory($categories[$this->faker->numberBetween(0,9)])
                        ->setThumbnail("placeholder.jpg")
                        ->setUser($user);
            $manager->persist($article);
        }

        $manager->flush();
    }

    private function generateDateTime():string {
        return $this->faker->dateTimeBetween(new \DateTime("2021-01-01"),new \DateTime("2021-12-30"))->format("Y/m/d H:i:s");
    }
}

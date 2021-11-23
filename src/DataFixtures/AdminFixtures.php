<?php

namespace App\DataFixtures;

use App\Entity\Page;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
        $hasher;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user   ->setEmail($_ENV["ADMIN_EMAIL"])
                ->setRoles(["ROLE_ADMIN"])
                ->setCreatedAt(new \DateTimeImmutable())
                ->setIsVerified(true);
        $password = $this->hasher->hashPassword($user,$_ENV["ADMIN_PLAIN_PASSWORD"]);
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

        $manager->flush();

    }

    public static function getGroups():array
    {
        return ["admin"];
    }
}

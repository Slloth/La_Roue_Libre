<?php

namespace App\DataFixtures;

use App\Entity\Page;
use App\Entity\TypeAdhesion;
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
                ->setRoles(["ROLE_ADMIN","ROLE_REDACTEUR","ROLE_ACCUEIL"])
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

        $typeAdhesions = ["Etudiant et chercheur d'emploi",12,"Salarié",26,"Famille",46];
            $adhesionType = new TypeAdhesion();
            $adhesionType   ->setTypeAdhesion($typeAdhesions[0])
                            ->setPrix($typeAdhesions[1])
                            ;
            $manager->persist($adhesionType);

            $adhesionType = new TypeAdhesion();
            $adhesionType   ->setTypeAdhesion($typeAdhesions[2])
                            ->setPrix($typeAdhesions[3])
                            ;
            $manager->persist($adhesionType);

            $adhesionType = new TypeAdhesion();
            $adhesionType   ->setTypeAdhesion($typeAdhesions[4])
                            ->setPrix($typeAdhesions[5])
                            ;
            $manager->persist($adhesionType);

        $manager->flush();

    }

    public static function getGroups():array
    {
        return ["admin"];
    }
}

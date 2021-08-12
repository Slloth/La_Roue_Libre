<?php

namespace App\Tests\Entity;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class UserTest extends KernelTestCase
{
    private ?ValidatorInterface $validator;
    private ?EntityManager $em;
   
    /**
     * Before each test initialize the validatorInterface component
     *
     * @return void
     */
    protected function setUp(): void{
        $kernel = self::bootKernel();

        $this->validator = $kernel->getContainer()->get("validator");

        $this->em = $kernel->getContainer()->get('doctrine')->getManager();

        $this->deleteTableUser();
    }

    protected function tearDown(): void
    {
        $this->deleteTableUser(); 
    }


    /**
     * Test if User's Email is valid
     *
     * @return void
     */
    public function testEntityUserEmailIsValide(): void
    {
        $user = new User();
        $user   ->setEmail("a@b.c")
                ->setPassword("password")
                ->setIsVerified(true)
                ->setCreatedAt(new DateTimeImmutable())
        ;
        $this->getValidationErrors($user,0);
    }

    /**
     * Test if User's Email is not valid
     * 
     * @return void
     */
    public function testEntityUserEmailIsNotValide(): void
    {
        $user = new User();
        $user   ->setEmail("abc")
                ->setPassword("password")
                ->setCreatedAt(new DateTimeImmutable())
        ;
        $errors = $this->getValidationErrors($user,1);

        $this->assertEquals("Cette valeur n'est pas une adresse email valide.",$errors[0]->getMessage());
    }

    /**
     * Test if User is not verified
     *
     * @return void
     */
    public function testEntityUserIsNotVerified(): void
    {
        $user = new User();
        $user   ->setEmail("abc")
                ->setPassword("password")
                ->setCreatedAt(new DateTimeImmutable())
        ;
        
        $this->assertEquals(false ,$user->isVerified());
    }


    public function testHashPassword(): void
    {
        $user = new User();
        
        $user   ->setEmail("a@b.c") 
                ->setCreatedAt(new DateTimeImmutable())
        ;
        $passwordHasher = $this->passwordHasher();
        $user->setPassword($passwordHasher->hash('password'));
        $this->getValidationErrors($user,0);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function testEntityUserInsertInDatabase(): void
    {
        $user = new User();
        $user   ->setEmail("a@b.c")
                ->setPassword($this->passwordHasher()->hash("password")) 
        ;
        $this->em->persist($user);
        $this->em->flush();
        $result = $this->em->getRepository(User::class);
        $this->assertCount(1,$result->findAll());
    }


    private function passwordHasher(): PasswordHasherInterface
    {
        $factory = new PasswordHasherFactory([
            'common' => ['algorithm' => 'bcrypt'],
            'memory-hard' => ['algorithm' => 'sodium'],
        ]);
        return $factory->getPasswordHasher('common');
    }

    private function deleteTableUser(): void
    {
        $request = $this->em->getConnection()->prepare("DELETE FROM users;");

        $request->executeQuery();
    }

    /**
     * return 
     *
     * @param User $user
     * @param integer $nbErrorsExpected
     * @return ConstraintViolationListInterface<ConstraintViolationList>
     */
    private function getValidationErrors(User $user, int $nbErrorsExpected): ConstraintViolationListInterface
    {
       $errors = $this->validator->validate($user);
       $this->assertCount($nbErrorsExpected, $errors);
       return $errors;
    }
}

<?php

namespace App\Tests;

use DateTime;
use App\Entity\Page;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class PageTest extends KernelTestCase
{
    private ?ValidatorInterface $validator;
    private ?EntityManager $em;

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

    public function testEntityPageIsValide(): void
    {
        $page = new Page();
        $page   ->setName("page 1")
                ->setSlug("page-1")
                ->setContent("je suis un longtext")
                ->setStatus("Publique")
                ->setPublicatedAt(new DateTime("now"));
        $this->getValidationErrors($page,0);
    }

    public function testEntityPageIsNotValide(): void
    {
        $page = new Page();
        $page   ->setName("page 1")
                ->setSlug("page-1")
                ->setContent("je suis un longtext")
                ->setStatus("Publique");
        $errors = $this->getValidationErrors($page,1);

        $this->assertEquals("Cette valeur ne doit pas être vide.",$errors[0]->getMessage());
    }

    private function deleteTableUser(): void
    {
        $request = $this->em->getConnection()->prepare("DELETE FROM pages;");

        $request->executeQuery();
    }

    public function testEntityPageInsertInDatabase(): void
    {
        $page = new Page();
        $page   ->setName("page 1")
                ->setSlug("page-1")
                ->setContent("je suis un longtext")
                ->setStatus("Publique")
                ->setPublicatedAt(new DateTime("now"))
        ;
        $this->em->persist($page);
        $this->em->flush();
        $result = $this->em->getRepository(Page::class);
        $this->assertCount(1,$result->findAll());
    }

    /**
     * return 
     *
     * @param Page $page
     * @param integer $nbErrorsExpected
     * @return ConstraintViolationListInterface<ConstraintViolationList>
     */
    private function getValidationErrors(Page $page, int $nbErrorsExpected): ConstraintViolationListInterface
    {
       $errors = $this->validator->validate($page);
       $this->assertCount($nbErrorsExpected, $errors);
       return $errors;
    }
}

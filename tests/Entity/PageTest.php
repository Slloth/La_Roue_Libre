<?php

namespace App\Tests;

use DateTime;
use App\Entity\Page;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class PageTest extends KernelTestCase
{
    private ?EntityManager $em;

    protected function setUp(): void{
        $kernel = self::bootKernel();

        $this->validator = $kernel->getContainer()->get("validator");

        $this->em = $kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testEntityPageIsValide()
    {
        $page = new Page();
        $page   ->setName("page 1")
                ->setSlug("page-1")
                ->setContent("je suis un longtext")
                ->setStatus("Publique")
                ->setPublicatedAt(new DateTime("now"));
        dd(new DateTime());
        $this->em->persist($page);
        $this->em->flush();
        $this->getValidationErrors($page,0);
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

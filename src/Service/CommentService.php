<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Page;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CommentService
{

    public function __construct(
        private EntityManagerInterface $em, 
        private FlashBagInterface $flash, 
        private CommentRepository $commentRepository
        )
    {
        $em;
        $flash;
        $commentRepository;
    }

    public function comment(FormInterface $commentForm, Page $page)
    {
        $parentId = $commentForm->get("parentId")->getData();
        $comment = new Comment();
        $comment->setContent($commentForm->get("content")->getData())
                ->setPage($page)
                ->setParent($this->commentRepository->find($parentId))
                ->setIsChecked(false)
                ;
        
        $this->em->persist($comment);
        $this->em->flush();
        $this->flash->add('success','Votre Commentaire à bien été envoyé, il sera traité et vérifier par un administrateur avant son affichage, merci !');
    }
}
<?php

namespace App\Service;

use App\Entity\Article;
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

    /**
     * Effectue l'enregistrement des données du formulaire de commentaire en base de données
     *
     * @param FormInterface $commentForm
     * @param Page $page
     * @param Article $article
     * @return void
     */
    public function persistComment(FormInterface $commentForm, Page $page = null, Article $article = null)
    {
        $parentId = $commentForm->get("parentId")->getData();
        $comment = new Comment();
        $comment->setContent($commentForm->get("content")->getData());
        
        if($page){
            $comment->setPage($page);
        }
        if($article)
        {
            $comment->setArticle($article);
        }
        $comment->setIsChecked(false);

        if($parentId !== null){
            $comment->setParent($this->commentRepository->find($parentId));
        }
        
        $this->em->persist($comment);
        $this->em->flush();
        $this->flash->add('success','Votre Commentaire à bien été envoyé, il sera traité et vérifier par un administrateur avant son affichage, merci !');
    }
}
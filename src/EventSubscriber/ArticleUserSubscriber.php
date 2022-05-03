<?php

namespace App\EventSubscriber;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class ArticleUserSubscriber implements EventSubscriberInterface
{
    //private $security;

    public function __construct(private Security $security)
    {
        $security;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setArticleCreatedUser'],
            BeforeEntityUpdatedEvent::class => ['setArticleUpdatedUser'],
        ];
    }

    /**
     * Avant chaque persistance d'un article en base de données, inscrit l'utilisateur qui l'as crée
     *
     * @param BeforeEntityPersistedEvent $event
     * 
     * @return void
     */
    public function setArticleCreatedUser(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if(!($entity instanceof Article)){
            return;
        }
        $entity->setUser($this->security->getUser());

    }
    
    /**
     * Avant chaque modification d'un article en base de données, modifi l'utilisateur qui l'as modifié
     *
     * @param BeforeEntityUpdatedEvent $event
     * 
     * @return void
     */
    public function setArticleUpdatedUser(BeforeEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if(!($entity instanceof Article)){
            return;
        }
        $entity->setUser($this->security->getUser());
    }
}
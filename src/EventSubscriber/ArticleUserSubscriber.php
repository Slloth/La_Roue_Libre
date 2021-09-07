<?php

namespace App\EventSubscriber;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class ArticleUserSubscriber implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setArticleCreatedUser'],
            BeforeEntityUpdatedEvent::class => ['setArticleUpdatedUser'],
        ];
    }

    public function setArticleCreatedUser(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if(!($entity instanceof Article)){
            return;
        }
        $entity->setUser($this->security->getUser());

    }
    
    public function setArticleUpdatedUser(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if(!($entity instanceof Article)){
            return;
        }
        $entity->setUser($this->security->getUser());
    }
}
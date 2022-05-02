<?php

namespace App\EventSubscriber;

use App\Entity\Adherent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RedirectionSubsriber implements EventSubscriberInterface
{
    public function __construct(private UrlGeneratorInterface $router) {
    }

    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => ['setRedirectionEvent']
        ];
    }
    public function setRedirectionEvent(RequestEvent $event){
        /*$entity = $event->getRequest()->request->get('Adherent');
        $entity = new Adherent($entity);
        if(!($entity instanceof Adherent)){
            return;
        }
        $url = $this->router->generate('admin',[
            'crudAction' => 'new',
            'crudControllerFqcn' => 'App%5CController%5CAdmin%5CAdhesionCrudController',
            'menuIndex' => '5',
            'signature' => '5z0Y264gyITR_O6-NFpBv5_TRSF7QaYiXFa2kc2sIvE'
         ]);
        //return $event->setResponse(new RedirectResponse($url));*/
    }
}
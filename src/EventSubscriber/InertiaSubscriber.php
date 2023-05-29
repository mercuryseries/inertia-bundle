<?php

namespace MercurySeries\Bundle\InertiaBundle\EventSubscriber;

use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class InertiaSubscriber implements EventSubscriberInterface
{
    private $inertia;
    
    public function __construct(InertiaInterface $routingHistory)
    {
        $this->inertia = $inertia;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $this->inertia->share(
            'errors',
            fn () => $event->getRequest()->getSession()->remove('errors')
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}

<?php

namespace MercurySeries\Bundle\InertiaMaker\EventSubscriber;

use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class InertiaSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly InertiaInterface $inertia)
    {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $this->inertia->share(
            'errors',
            fn () => $event->getRequest()->getSession()->remove('errors')
        );

        $this->inertia->share(
            'flash',
            [
                'success' => fn () => $event->getRequest()
                    ->getSession()
                    ->getFlashBag()
                    ->get('success')[0] ?? '',
                'error' => fn () => $event->getRequest()
                    ->getSession()
                    ->getFlashBag()
                    ->get('error')[0] ?? '',
            ],
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}

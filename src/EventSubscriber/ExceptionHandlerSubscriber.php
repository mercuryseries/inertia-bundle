<?php

namespace MercurySeries\Bundle\InertiaMaker\EventSubscriber;

use MercurySeries\Bundle\InertiaMaker\Exception\InvalidFormException;
use MercurySeries\Bundle\InertiaMaker\Service\RoutingHistory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionHandlerSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly RoutingHistory $routingHistory,
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof InvalidFormException) {
            $request = $event->getRequest();
            $previousUrl = $this->routingHistory->getPreviousUrl();

            $request->getSession()->set('errors', $exception->getFormErrors());

            $event->setResponse(new RedirectResponse($previousUrl));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}

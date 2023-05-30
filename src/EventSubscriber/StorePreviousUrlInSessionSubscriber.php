<?php

namespace MercurySeries\Bundle\InertiaBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class StorePreviousUrlInSessionSubscriber implements EventSubscriberInterface
{
    public function __construct(string $previousUrlSessionKey)
    {
        $this->previousUrlSessionKey = $previousUrlSessionKey;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (
            $request->isMethod('GET') &&
            !$request->isXmlHttpRequest() &&
            !$this->isNotPrefetch($request)
        ) {
            $request->getSession()->set(
                $this->previousUrlSessionKey,
                $request->getUri()
            );
        }
    }

    /**
     * Determine if the request is the result of a prefetch call.
     */
    private function isNotPrefetch(Request $request): bool
    {
        return 0 === strcasecmp($request->server->get('HTTP_X_MOZ') ?? '', 'prefetch') ||
               0 === strcasecmp($request->headers->get('Purpose') ?? '', 'prefetch');
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 15],
        ];
    }
}

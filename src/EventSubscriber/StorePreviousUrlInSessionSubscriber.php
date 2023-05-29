<?php

namespace MercurySeries\Bundle\InertiaMakerBundle\EventSubscriber;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class StorePreviousUrlInSessionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        #[Autowire('%mercuryseries_inertia_maker.previous_url_session_key%')]
        private readonly string $previousUrlSessionKey,
    ) {
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

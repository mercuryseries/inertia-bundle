<?php

namespace MercurySeries\Bundle\InertiaMakerBundle\EventSubscriber\Form;

use App\Exception\InvalidFormException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CheckFormValidationStateSubscriber implements EventSubscriberInterface
{
    public function onPostSubmit(FormEvent $event): void
    {
        $form = $event->getForm();

        if (!$form->isValid()) {
            throw new InvalidFormException($form);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SUBMIT => 'onPostSubmit',
        ];
    }
}

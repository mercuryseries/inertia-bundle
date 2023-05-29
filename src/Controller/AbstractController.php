<?php

namespace MercurySeries\Bundle\InertiaMakerBundle\Controller;

use MercurySeries\Bundle\InertiaMakerBundle\EventSubscriber\Form\CheckFormValidationStateSubscriber;
use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as FrameworkBundleAbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController extends FrameworkBundleAbstractController
// abstract class AbstractController extends FrameworkBundleAbstractController
{
    /**
     * Creates and returns an unnamed form instance from the type of the form.
     */
    protected function createForm(string $type, mixed $data = null, array $options = []): FormInterface
    {
        return $this->container->get('form.factory')
            ->createNamedBuilder('', $type, $data, $options)
            ->addEventSubscriber(
                $this->container->get('mercuryseries_inertia_maker.event_subscriber.check_form_validation_state_subscriber')
            )
            ->getForm()
        ;
    }

    /**
     * Renders a component as a page.
     */
    protected function inertiaRender($component, $props = [], $viewData = [], $context = []): Response
    {
        return $this->container->get('rompetomp_inertia.inertia')
            ->render($component, $props, $viewData, $context)
        ;
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            // 'rompetomp_inertia.inertia' => InertiaInterface::class,
            // 'mercuryseries_inertia_maker.event_subscriber.check_form_validation_state_subscriber' =>  CheckFormValidationStateSubscriber::class,
        ]);
    }
}

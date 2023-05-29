<?php

namespace MercurySeries\Bundle\InertiaMakerBundle\Controller;

use MercurySeries\Bundle\InertiaMakerBundle\EventSubscriber\Form\CheckFormValidationStateSubscriber;
use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController extends SymfonyAbstractController
{
    /**
     * Creates and returns an unnamed form instance from the type of the form.
     */
    protected function createForm(string $type, mixed $data = null, array $options = []): FormInterface
    {
        return $this->container->get('form.factory')
            ->createNamedBuilder('', $type, $data, $options)
            ->addEventSubscriber(
                $this->container->get(CheckFormValidationStateSubscriber::class)
            )
            ->getForm()
        ;
    }

    /**
     * Renders a component as a page.
     */
    protected function inertiaRender($component, $props = [], $viewData = [], $context = []): Response
    {
        return $this->container->get(InertiaInterface::class)
            ->render($component, $props, $viewData, $context)
        ;
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            InertiaInterface::class,
            CheckFormValidationStateSubscriber::class,
        ]);
    }
}

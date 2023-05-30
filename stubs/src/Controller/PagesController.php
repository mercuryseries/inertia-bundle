<?php

namespace App\Controller;

use App\Form\ContactFormType;
use MercurySeries\Bundle\InertiaBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'], options: ['expose' => true])]
    public function home(): Response
    {
        return $this->inertiaRender('Home', ['name' => 'John Doe']);
    }

    #[Route('/about-us', name: 'app_about', methods: ['GET'], options: ['expose' => true])]
    public function about(): Response
    {
        return $this->inertiaRender('About');
    }

    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'], options: ['expose' => true])]
    public function contact(Request $request): Response
    {
        $form = $this->createForm(ContactFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Message sent successfully!');

            return $this->redirectToRoute('app_home');
        }

        return $this->inertiaRender('Contact');
    }
}

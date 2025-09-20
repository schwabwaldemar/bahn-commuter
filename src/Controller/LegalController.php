<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LegalController extends AbstractController
{
    #[Route(path: '/{_locale?en}/legal/terms', name: 'app_terms', requirements: ['_locale' => 'en|de'])]
    public function terms(): Response
    {
        return $this->render('legal/terms.html.twig');
    }

    #[Route(path: '/{_locale?en}/legal/privacy', name: 'app_privacy', requirements: ['_locale' => 'en|de'])]
    public function privacy(): Response
    {
        return $this->render('legal/privacy.html.twig');
    }
}

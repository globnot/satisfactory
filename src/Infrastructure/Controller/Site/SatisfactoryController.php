<?php

namespace App\Infrastructure\Controller\Site;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SatisfactoryController extends AbstractController
{
    #[Route('/satisfactory', name: 'app_satisfactory')]
    public function index(): Response
    {
        return $this->render(
            'site/satisfactory/index.html.twig'
        );
    }
}

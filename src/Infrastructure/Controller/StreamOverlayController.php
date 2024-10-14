<?php

namespace App\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StreamOverlayController extends AbstractController
{
    #[Route('/stream/overlay', name: 'stream_overlay')]
    public function index(): Response
    {
        return $this->render(
            'stream/overlay/index.html.twig'
        );
    }
}

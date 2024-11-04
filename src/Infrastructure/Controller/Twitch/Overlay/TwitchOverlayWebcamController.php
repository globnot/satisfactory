<?php

namespace App\Infrastructure\Controller\Twitch\Overlay;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TwitchOverlayWebcamController extends AbstractController
{
    #[Route('/twitch/overlay/webcam', name: 'twitch_overlay_webcam')]
    public function index(): Response
    {
        return $this->render('twitch/overlay/webcam/index.html.twig');
    }
}

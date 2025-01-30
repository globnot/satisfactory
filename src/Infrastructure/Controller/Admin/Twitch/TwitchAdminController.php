<?php

namespace App\Infrastructure\Controller\Admin\Twitch;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TwitchAdminController extends AbstractController
{
    #[Route('/admin/twitch', name: 'admintwitch')]
    public function index(): Response
    {
        return $this->render('admin/twitch/index.html.twig', [
        ]);
    }
}

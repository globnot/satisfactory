<?php

namespace App\Infrastructure\Controller\Site;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Infrastructure\Persistence\Repository\Site\SatisfactoryBpRepository;
use Symfony\Component\Serializer\SerializerInterface;

class SatisfactoryController extends AbstractController
{
    public function __construct(
        private SatisfactoryBpRepository $satisfactoryBpRepository,
        private SerializerInterface $serializer
    ) {
    }

    #[Route('/satisfactory/blueprints', name: 'app_satisfactory_blueprints')]
    public function blueprints(): Response
    {
        $blueprints = $this->satisfactoryBpRepository->findAll();

        $blocks = array_map(function ($blueprint) {
            return [
                'title' => $blueprint->getTitle(),
                'description' => $blueprint->getDescription(),
                'author' => $blueprint->getAuthor(),
                'createdAt' => $blueprint->getCreatedAt()->format('Y-m-d H:i:s'),
                'updatedAt' => $blueprint->getUpdatedAt()->format('Y-m-d H:i:s'),
                'comments' => array_map(function ($comment) {
                    return [
                        'author' => $comment->getAuthor(),
                        'content' => $comment->getContent(),
                        'createdAt' => $comment->getCreatedAt()->format('Y-m-d H:i:s'),
                    ];
                }, $blueprint->getComment()->toArray()),
                'downloadUrlSbp' => $blueprint->getDownloadUrlSbp(),
                'downloadUrlSbpcfg' => $blueprint->getDownloadUrlSbpcfg(),
                'downloadCount' => $blueprint->getDownloadCount(),
                'images' => array_map(function ($image) {
                    return '/uploads/satisfactory_bp/' . $image->getImageName();
                }, $blueprint->getImage()->toArray()),
            ];
        }, $blueprints);

        return $this->render(
            'site/satisfactory/blueprints.html.twig',
            [
                'blocks' => $blocks
            ]
        );
    }
}

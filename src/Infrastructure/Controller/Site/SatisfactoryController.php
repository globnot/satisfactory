<?php

namespace App\Infrastructure\Controller\Site;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Infrastructure\Persistence\Repository\Site\SatisfactoryBpRepository;

class SatisfactoryController extends AbstractController
{
    public function __construct(
        private SatisfactoryBpRepository $satisfactoryBpRepository,
        private SerializerInterface $serializer,
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/satisfactory/blueprints', name: 'app_satisfactory_blueprints')]
    public function blueprints(): Response
    {
        $blueprints = $this->satisfactoryBpRepository->findAll();

        $blocks = array_map(function ($blueprint) {
            return [
                'id' => $blueprint->getId(),
                'title' => $blueprint->getTitle(),
                'description' => $blueprint->getDescription(),
                'author' => $blueprint->getAuthor(),
                'createdAt' => $blueprint->getCreatedAt()->format('Y-m-d H:i:s'),
                'updatedAt' => $blueprint->getUpdatedAt()->format('Y-m-d H:i:s'),
                'downloadCount' => $blueprint->getDownloadCount(),
                'thankCount' => $blueprint->getThankCount(),
                'images' => array_map(function ($image) {
                    return '/uploads/satisfactory_bp/' . $image->getImageName();
                }, $blueprint->getImage()->toArray()),
                'sbp' => array_map(function ($sbp) {
                    return '/uploads/satisfactory_sbp/' . $sbp->getSbpName();
                }, $blueprint->getSbp()->toArray()),
                'sbpcfg' => array_map(function ($sbpcfg) {
                    return '/uploads/satisfactory_sbpcfg/' . $sbpcfg->getSbpcfgName();
                }, $blueprint->getSbpcfg()->toArray()),
            ];
        }, $blueprints);

        return $this->render(
            'site/satisfactory/blueprints.html.twig',
            [
                'blocks' => $blocks,
            ]
        );
    }

    #[Route('/satisfactory/blueprint/{id}/download/sbp', name: 'app_satisfactory_download_sbp')]
    public function downloadSbp(int $id): Response
    {
        $blueprint = $this->satisfactoryBpRepository->find($id);

        if (!$blueprint) {
            throw $this->createNotFoundException('Blueprint not found');
        }

        $sbpFiles = $blueprint->getSbp();
        if (0 === count($sbpFiles)) {
            throw $this->createNotFoundException('No SBP files found for this blueprint');
        }

        // Télécharger le premier fichier SBP
        $sbpFile = $sbpFiles[0];
        $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/satisfactory_sbp/' . $sbpFile->getSbpName();

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('File not found');
        }

        // Incrémenter le compteur de téléchargements
        $blueprint->incrementDownloadCount();
        $this->entityManager->persist($blueprint);
        $this->entityManager->flush();

        // Créer la réponse de téléchargement
        $response = new BinaryFileResponse($filePath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $sbpFile->getSbpName());

        return $response;
    }

    #[Route('/satisfactory/blueprint/{id}/download/sbpcfg', name: 'app_satisfactory_download_sbpcfg')]
    public function downloadSbpcfg(int $id): Response
    {
        $blueprint = $this->satisfactoryBpRepository->find($id);
        if (!$blueprint) {
            throw $this->createNotFoundException('Blueprint not found');
        }

        $sbpcfgFiles = $blueprint->getSbpcfg();
        if (0 === count($sbpcfgFiles)) {
            throw $this->createNotFoundException('No SBPCFG files found for this blueprint');
        }

        // Télécharger le premier fichier SBPCFG
        $sbpcfgFile = $sbpcfgFiles[0];
        $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/satisfactory_sbpcfg/' . $sbpcfgFile->getSbpcfgName();

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('File not found');
        }

        // Incrémenter le compteur de téléchargements
        $blueprint->incrementDownloadCount();
        $this->entityManager->persist($blueprint);
        $this->entityManager->flush();

        // Créer la réponse de téléchargement
        $response = new BinaryFileResponse($filePath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $sbpcfgFile->getSbpcfgName());

        return $response;
    }

    #[Route('/satisfactory/blueprint/{id}/thank', name: 'app_satisfactory_thank', methods: ['POST'])]
    public function thank(
        int $id,
        SessionInterface $session
    ): Response {
        $blueprint = $this->satisfactoryBpRepository->find($id);
        if (!$blueprint) {
            return new JsonResponse(['error' => 'Blueprint introuvable'], Response::HTTP_NOT_FOUND);
        }

        // Récupérer les blueprints déjà remerciés depuis la session
        $thankedBlueprints = $session->get('thanked_blueprints', []);

        if (in_array($id, $thankedBlueprints)) {
            return new JsonResponse(['error' => 'Vous avez déjà remercié pour ce blueprint'], Response::HTTP_BAD_REQUEST);
        }

        // Ajouter le blueprint aux blueprints remerciés
        $thankedBlueprints[] = $id;
        $session->set('thanked_blueprints', $thankedBlueprints);

        // Incrémenter le compteur de remerciements
        $blueprint->incrementThankCount();
        $this->entityManager->persist($blueprint);
        $this->entityManager->flush();

        return new JsonResponse(['thankCount' => $blueprint->getThankCount()]);
    }
}

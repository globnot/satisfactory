<?php

namespace App\Infrastructure\Controller\Admin;

use App\Domain\Entity\Twitch\TwitchChatRanking;
use App\Domain\Entity\Twitch\TwitchChatVote;
use App\Domain\Entity\Twitch\TwitchChatVoteSession;
use App\Infrastructure\Persistence\Repository\Twitch\TwitchChatVoteSessionRepository;
use App\Infrastructure\Service\Twitch\TwitchChatMessageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoteSessionController extends AbstractController
{
    public function __construct(
        private TwitchChatMessageService $twitchChatMessageService,
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/admin/twitch/vote-session', name: 'admin_vote_session_index')]
    public function index(TwitchChatVoteSessionRepository $voteSessionRepository): Response
    {
        $voteSessions = $voteSessionRepository->findBy([], ['startedAt' => 'DESC']);

        return $this->render('admin/twitch/index.html.twig', [
            'vote_sessions' => $voteSessions,
        ]);
    }

    #[Route('/admin/twitch/vote-session-new', name: 'admin_vote_session_new')]
    public function new(Request $request): Response
    {
        // Création d'une nouvelle session de vote
        $voteSession = new TwitchChatVoteSession();
        $voteSession->setState(TwitchChatVoteSession::STATE_STARTED);
        $voteSession->setStartedAt(new \DateTimeImmutable());

        $this->entityManager->persist($voteSession);
        $this->entityManager->flush();

        // Envoyer un message au chat Twitch pour annoncer le début du vote
        $this->twitchChatMessageService->sendMessage('Le vote est ouvert ! Envoyez votre vote en tapant ![nombre]');

        return $this->redirectToRoute('admin_vote_session_index');
    }

    #[Route('/admin/twitch/vote-session/{id}/suspend', name: 'admin_vote_session_suspend')]
    public function suspend(TwitchChatVoteSession $voteSession): Response
    {
        $voteSession->setState(TwitchChatVoteSession::STATE_SUSPENDED);
        $this->entityManager->flush();

        // Envoyer un message au chat Twitch pour annoncer la suspension du vote
        $this->twitchChatMessageService->sendMessage('Le vote est suspendu. Merci de patienter.');

        return $this->redirectToRoute('admin_vote_session_index');
    }

    public function resume(TwitchChatVoteSession $voteSession): Response
    {
        $voteSession->setState(TwitchChatVoteSession::STATE_STARTED);
        $this->entityManager->flush();

        // Envoyer un message au chat Twitch pour annoncer la reprise du vote
        $this->twitchChatMessageService->sendMessage('Le vote reprend ! Envoyez votre vote en tapant ![nombre]');

        return $this->redirectToRoute('admin_vote_session_index');
    }

    #[Route('/admin/twitch/vote-session/{id}/close', name: 'admin_vote_session_close')]
    public function close(Request $request, TwitchChatVoteSession $voteSession): Response
    {
        if ($request->isMethod('POST')) {
            $correctAnswer = (int) $request->request->get('correct_answer');
            $voteSession->setCorrectAnswer($correctAnswer);
            $voteSession->setState(TwitchChatVoteSession::STATE_ENDED);
            $voteSession->setEndedAt(new \DateTimeImmutable());
            $this->entityManager->flush();

            // Calculer les résultats et envoyer les messages
            $this->calculateResultsAndAnnounce($voteSession);

            return $this->redirectToRoute('admin_vote_session_index');
        }

        return $this->render('admin/vote_session/close.html.twig', [
            'vote_session' => $voteSession,
        ]);
    }

    #[Route('/admin/twitch/vote-result/{id}/', name: 'admin_vote_session_result')]
    public function calculateResultsAndAnnounce(TwitchChatVoteSession $voteSession): void
    {
        $votes = $this->entityManager->getRepository(TwitchChatVote::class)->findBy(['twitchChatVoteSession' => $voteSession]);

        $correctAnswer = $voteSession->getCorrectAnswer();

        // Calculer les différences
        $results = [];
        foreach ($votes as $vote) {
            $difference = abs($vote->getValue() - $correctAnswer);
            $results[] = [
                'viewer' => $vote->getTwitchChatViewer(),
                'difference' => $difference,
                'timestamp' => $vote->getTimestamp(),
            ];
        }

        // Trier les résultats
        usort($results, function ($a, $b) {
            if ($a['difference'] == $b['difference']) {
                return $a['timestamp'] <=> $b['timestamp'];
            }

            return $a['difference'] <=> $b['difference'];
        });

        // Attribuer les points
        $pointsDistribution = [5, 4, 3, 2, 1];

        for ($i = 0; $i < min(5, count($results)); ++$i) {
            $viewer = $results[$i]['viewer'];
            $points = $pointsDistribution[$i];

            // Mettre à jour le total des points du viewer
            $viewer->setTotalPoints($viewer->getTotalPoints() + $points);
            $this->entityManager->persist($viewer);

            // Enregistrer le classement
            $ranking = new TwitchChatRanking();
            $ranking->setTwitchChatVoteSession($voteSession);
            $ranking->setTwitchChatViewer($viewer);
            $ranking->setPoints($points);
            $ranking->setRank($i + 1);
            $this->entityManager->persist($ranking);
        }

        $this->entityManager->flush();

        // Préparer le message des résultats
        $message = 'Résultats du vote :';
        for ($i = 0; $i < min(5, count($results)); ++$i) {
            $viewer = $results[$i]['viewer'];
            $message .= "\n".($i + 1).'. '.$viewer->getId()." (+{$pointsDistribution[$i]} points)";
        }

        // Envoyer le message au chat Twitch
        $this->twitchChatMessageService->sendMessage($message);
    }
}

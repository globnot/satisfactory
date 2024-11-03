<?php

namespace App\Command;

use App\Infrastructure\Persistence\Repository\Twitch\TwitchChatVoteRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:close-votes',
    description: 'Close the votes and announce the winners'
)]
class StopTwitchBotCommand extends Command
{
    public function __construct(
        private TwitchChatVoteRepository $twitchChatVoteRepository,
    ) {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Supposons que la bonne réponse est stockée quelque part
        $correctAnswer = 9999;

        $votes = $this->twitchChatVoteRepository->findAll();

        $winners = array_filter($votes, function ($vote) use ($correctAnswer) {
            return $vote->getGuess() === $correctAnswer;
        });

        if (empty($winners)) {
            $output->writeln('Aucun gagnant cette fois-ci.');
        } else {
            $output->writeln('Les gagnants sont :');
            foreach ($winners as $winner) {
                $output->writeln($winner->getUsername());
            }
        }

        return Command::SUCCESS;
    }
}

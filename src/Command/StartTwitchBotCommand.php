<?php

namespace App\Command;

use App\Application\Interface\Twitch\TwitchChatBotInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:twitchbot-start',
    description: 'Démarre le bot Twitch'
)]
class StartTwitchBotCommand extends Command
{
    public function __construct(
        private TwitchChatBotInterface $twitchChatBotService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Démarrage du bot Twitch');

        try {
            $this->twitchChatBotService->run();
            $io->success('Le bot Twitch a démarré avec succès.');
        } catch (\Exception $e) {
            $io->error('Erreur lors du démarrage du bot Twitch : '.$e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

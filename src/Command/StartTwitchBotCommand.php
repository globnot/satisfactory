<?php

namespace App\Command;

use App\Infrastructure\Service\Twitch\TwitchChatBotService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:twitchbot-start',
    description: 'Start the Twitch bot'
)]
class StartTwitchBotCommand extends Command
{
    public function __construct(
        private TwitchChatBotService $twitchChatBotService,
    ) {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->twitchChatBotService->run();

        return Command::SUCCESS;
    }
}

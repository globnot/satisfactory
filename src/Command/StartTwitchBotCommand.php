<?php

namespace App\Command;

use App\Infrastructure\Service\Twitch\TwitchChatBotService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:start-twitch-bot',
    description: 'Start the Twitch bot'
)]
class StartTwitchBotCommand extends Command
{
    private TwitchChatBotService $twitchChatBot;

    public function __construct(TwitchChatBotService $twitchChatBot)
    {
        parent::__construct();
        $this->twitchChatBot = $twitchChatBot;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->twitchChatBot->run();

        return Command::SUCCESS;
    }
}

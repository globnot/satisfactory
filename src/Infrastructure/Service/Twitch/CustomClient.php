<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Twitch;

use GhostZero\Tmi\Client as BaseClient;
use GhostZero\Tmi\ClientOptions;
use React\EventLoop\LoopInterface;
use React\Promise\PromiseInterface;
use React\Socket\Connector as SocketConnector;

class CustomClient extends BaseClient
{
    protected LoopInterface $loop;

    public function __construct(ClientOptions $options, LoopInterface $loop = null)
    {
        parent::__construct($options);

        // Si une boucle est fournie, utilisez-la, sinon créez-en une nouvelle
        $this->loop = $loop ?? \React\EventLoop\Loop::get();
    }

    protected function getConnectorPromise(): PromiseInterface
    {
        // Création d'un connecteur avec 'dns' => false pour utiliser le résolveur DNS système
        $connector = new SocketConnector($this->loop, [
            'dns' => false,
            'tcp' => true,
            'tls' => true,
        ]);

        // Récupérer les options via les méthodes appropriées
        $secure = $this->options->connection['secure'] ?? false;
        $server = $this->options->options['server'] ?? 'irc.chat.twitch.tv';
        $port = $this->options->options['port'] ?? ($secure ? 6697 : 6667);

        $uri = ($secure ? 'tls://' : 'tcp://') . $server . ':' . $port;

        return $connector->connect($uri);
    }

    public function getLoop(): LoopInterface
    {
        return $this->loop;
    }
}

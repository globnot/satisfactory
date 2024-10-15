<?php

namespace App\Tests\Infrastructure\Client\Twitch;

use App\Infrastructure\Client\Twitch\TwitchApiClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class TwitchApiClientTest extends TestCase
{
    private HttpClientInterface&MockObject $httpClient;
    private TwitchApiClient $twitchApiClient;
    private string $clientId = 'test_client_id';
    private string $clientSecret = 'test_client_secret';

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->twitchApiClient = new TwitchApiClient(
            $this->httpClient,
            $this->clientId,
            $this->clientSecret
        );
    }
    
    public function testGetAuthorizationUrl()
    {
        $redirectUri = 'https://example.com/callback';
        $scopes = 'user:read:email';

        $expectedUrl = 'https://id.twitch.tv/oauth2/authorize?' . http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => $scopes,
        ]);

        $url = $this->twitchApiClient->getAuthorizationUrl($redirectUri, $scopes);

        $this->assertEquals($expectedUrl, $url);
    }

    public function testExchangeCodeForTokenSuccess()
    {
        $code = 'test_code';
        $redirectUri = 'https://example.com/callback';

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(200);
        $mockResponse->method('toArray')->willReturn([
            'access_token' => 'test_access_token',
            'refresh_token' => 'test_refresh_token',
            'expires_in' => 3600,
            'scope' => ['user:read:email'],
            'token_type' => 'bearer',
        ]);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                'https://id.twitch.tv/oauth2/token',
                [
                    'body' => [
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                        'code' => $code,
                        'grant_type' => 'authorization_code',
                        'redirect_uri' => $redirectUri,
                    ],
                ]
            )
            ->willReturn($mockResponse);

        $result = $this->twitchApiClient->exchangeCodeForToken($code, $redirectUri);

        $this->assertIsArray($result);
        $this->assertEquals('test_access_token', $result['access_token']);
    }

    public function testExchangeCodeForTokenFailure()
    {
        $code = 'invalid_code';
        $redirectUri = 'https://example.com/callback';

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(400);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->willReturn($mockResponse);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Échec lors de l'obtention du jeton d'accès");

        $this->twitchApiClient->exchangeCodeForToken($code, $redirectUri);
    }

    public function testRefreshAccessTokenSuccess()
    {
        $refreshToken = 'test_refresh_token';

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(200);
        $mockResponse->method('toArray')->willReturn([
            'access_token' => 'new_access_token',
            'refresh_token' => 'new_refresh_token',
            'expires_in' => 3600,
            'scope' => ['user:read:email'],
            'token_type' => 'bearer',
        ]);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                'https://id.twitch.tv/oauth2/token',
                [
                    'body' => [
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $refreshToken,
                    ],
                ]
            )
            ->willReturn($mockResponse);

        $result = $this->twitchApiClient->refreshAccessToken($refreshToken);

        $this->assertIsArray($result);
        $this->assertEquals('new_access_token', $result['access_token']);
    }

    public function testRefreshAccessTokenFailure()
    {
        $refreshToken = 'invalid_refresh_token';

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(400);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->willReturn($mockResponse);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Échec lors du rafraîchissement du jeton d'accès");

        $this->twitchApiClient->refreshAccessToken($refreshToken);
    }
}

<?php

use Google\Client;
use Google\Service\Indexing;

final class GoogleFastIndexing
{
    private Client $client;

    /**
     * @param string $keyPath json файл из личного аккаунта разработчика Google
     * @throws \Google\Exception
     */
    public function __construct(string $keyPath)
    {
        /** @var string keyPath */
        $this->keyPath = $keyPath;

        $this->client = new Client();
        $this->client->setAuthConfig($keyPath);
        $this->client->addScope('https://www.googleapis.com/auth/indexing');
    }

    /**
     * @param string $urlsPath
     * @return Indexing\PublishUrlNotificationResponse
     */
    public function send(string $urlsPath): Indexing\PublishUrlNotificationResponse|string
    {
        $service = new Indexing($this->client);

        $batch = explode("\n", file_get_contents($urlsPath));

        try {
            $this->client->fetchAccessTokenWithAssertion();

            $items = array_map(function($line) {
                return [
                    'Content-Type' => 'application/http',
                    'Content-ID' => '',
                    'body' =>
                        "POST /v3/urlNotifications:publish HTTP/1.1\n" .
                        "Content-Type: application/json\n\n" .
                        json_encode([
                            'url' => $line,
                            'type' => 'URL_UPDATED'
                        ])
                ];
            }, $batch);

            return $service->urlNotifications->publish([
                'body' => ['urlNotification' => $items]
            ]);

        } catch (Exception $e) {
            return 'Ошибка: ' . $e->getMessage();
        }
    }
}
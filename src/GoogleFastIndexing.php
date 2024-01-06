<?php

use Firebase\JWT\JWT;
use GuzzleHttp\Client;

final class GoogleFastIndexing
{
    private Client $client;

    private string $jwt;

    /**
     * @param string $keyFile json файл из личного аккаунта разработчика Google
     * @throws \Google\Exception
     */
    public function __construct(string $keyFile)
    {
        $key = json_decode(file_get_contents($keyFile), true);

        $this->jwt = JWT::encode([
            'iss' => $key['client_email'],
            'scope' => 'https://www.googleapis.com/auth/indexing',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => time() + 3600,
            'iat' => time(),
        ], $key['private_key'], 'RS256');
    }

    public function send(string $batchFile)
    {
        $batch = explode("\n", file_get_contents($batchFile));

        $items = array_map(function ($line) {
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

        $client = new Client();
        $response = $client->request('POST', 'https://indexing.googleapis.com/batch', [
            'headers' => [
                'Content-Type' => 'multipart/mixed',
                'Authorization' => 'Bearer ' . $this->jwt
            ],
            'json' => $items
        ]);

        echo $response->getBody()->getContents();
    }
}
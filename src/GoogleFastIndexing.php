<?php

namespace dllpl;

use Firebase\JWT\JWT;
use GuzzleHttp\Client;

final class GoogleFastIndexing
{
    /** @var Client  */
    private Client $client;

    /** @var string  */
    private string $jwt;

    /**
     * @param string $keyFile json файл из личного аккаунта разработчика Google
     * @throws Exception
     */
    public function __construct(string $keyFile)
    {
        $this->jwt = $this->makeJWT($keyFile);
        $this->client = new Client();
    }

    /**
     * @param string $batchFile
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws Exception
     */
    public function send(string $batchFile): string
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

        try {
            $response = $this->client->request('POST', 'https://indexing.googleapis.com/batch', [
                'headers' => [
                    'Content-Type' => 'multipart/mixed',
                    'Authorization' => 'Bearer ' . $this->jwt
                ],
                'json' => $items
            ]);

            return $response->getBody()->getContents();
        } catch (\Exception $exception) {
            return throw new Exception($exception->getMessage());
        }
    }

    /**
     * @param string $keyFile
     * @return string
     * @throws Exception
     */
    private function makeJWT(string $keyFile): string
    {
        $key = json_decode(file_get_contents($keyFile), true);

        if (isset($key['client_email'], $key['private_key'])) {
            try {
                return JWT::encode([
                    'iss' => $key['client_email'],
                    'scope' => 'https://www.googleapis.com/auth/indexing',
                    'aud' => 'https://oauth2.googleapis.com/token',
                    'exp' => time() + 3600,
                    'iat' => time(),
                ], $key['private_key'], 'RS256');
            } catch (\Exception $exception) {
                return throw new Exception($exception->getMessage());
            }
        } else {
            return throw new Exception('Отсутствует обязательное поле client_email или private_key');
        }
    }
}
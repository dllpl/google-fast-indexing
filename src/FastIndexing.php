<?php

namespace Dllpl\Google;

final class FastIndexing
{
    /** @var \Google_Client */
    private \Google_Client $client;

    /** @var \Google_Service_Indexing */
    private \Google_Service_Indexing $service;

    /** @var \Google_Service_Indexing_UrlNotification */
    private \Google_Service_Indexing_UrlNotification $postBody;

    /**
     * @param string $keyPath
     * @throws \Google\Exception
     */
    public function __construct(string $keyPath)
    {
        $this->client = new \Google_Client();
        $this->client->setAuthConfig($keyPath);
        $this->client->addScope('https://www.googleapis.com/auth/indexing');
        $this->client->setUseBatch(true);

        $this->service = new \Google_Service_Indexing($this->client);

        $this->postBody = new \Google_Service_Indexing_UrlNotification();

    }

    /**
     * @param string $batchFile
     * @return array
     */
    public function send(string $batchFile): array
    {
        $file = file($batchFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $batch = $this->service->createBatch();
        $responses = [];
        foreach ($file as $key => $line) {
            $this->postBody->setType('URL_UPDATED');
            $this->postBody->setUrl($line);
            $batch->add($this->service->urlNotifications->publish($this->postBody));
            $response = $batch->execute();
            file_put_contents('response.log', 'Url--->' . $line . '---status--->' . $response['body'] . PHP_EOL, FILE_APPEND);
            $responses[$key] = $response;
        }
        return $responses;
    }
}

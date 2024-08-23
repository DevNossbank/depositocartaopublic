<?php
// app/Services/GuzzleService.php

namespace App\Services;

use GuzzleHttp\Client;


class GuzzleService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function sendRequest($method, $url, $body, $headers = [])
{
    //dd($method,$url,$body,$headers);
    // Cabeçalhos da requisição
    $requestOptions = [
        'headers' => $headers,
        'body' => $body,
        'verify' => false,
    ];

    // Exibe ou faz log dos cabeçalhos antes de enviar a requisição
    //dump($requestOptions);

    // Retorna a resposta da requisição
    $response = $this->client->request($method, $url, $requestOptions);

    // Cabeçalhos da resposta
    $responseHeaders = $response->getHeaders();
    //dump($responseHeaders);

    // Retorna a resposta da requisição
    return $response;
}
}
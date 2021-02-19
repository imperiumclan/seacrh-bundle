<?php

namespace ICS\SearchBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class QwantService
{
    private $client;

    public function __construct(ContainerInterface $container, HttpClientInterface $httpclient)
    {
        $this->client = $httpclient;
        $this->container = $container;
    }

    public function Search($searchValue, $nbResult = 30, $offset = 0, $type = 'web')
    {
        $requestOptions = [];

        $url = 'https://api.qwant.com/egp/search/' . $type . '/';

        $requestOptions['q'] = trim($searchValue);
        if ($offset > 0) {
            $requestOptions['offset'] = $offset;
        }

        if ('images' == $type) {
            $requestOptions['count'] = $nbResult;
            $requestOptions['size'] = 'large';
        }

        $options = '';
        if (count($requestOptions) > 0) {
            $options = '?';
            foreach ($requestOptions as $key => $opt) {
                $options .= $key . '=' . $opt . '&';
            }
            $options = substr($options, 0, strlen($options) - 1);
        }
        // dump($url . $options);
        $response = $this->client->request('GET', $url . $options, [
            'max_redirects' => 5,
            'headers' => [
                'User-Agent' => 'PostmanRuntime/7.26.10'
            ]
        ]);

        return json_decode($response->getContent());
    }

    // public function getApiUrl(, bool $raw = false)
    // {
    //     $options = '';
    //     if (count($requestOptions) > 0) {
    //         $options = '?';
    //         foreach ($requestOptions as $key => $opt) {
    //             $options .= $key.'='.$opt.'&';
    //         }
    //         $options = substr($options, 0, strlen($options) - 1);
    //     }

    //     $response = $this->client->request('GET', $url.$options, [
    //         'max_redirects' => 5,
    //     ]);
    //     $contentType = $response->getHeaders()['content-type'][0];
    //     // dump($url.$options);
    //     // dump($response->getContent());
    //     // $this->cookie = '';
    //     // dump($response->getHeaders());
    //     // foreach ($response->getHeaders()['set-cookie'] as $cookieLine) {
    //     //     $this->cookie = $this->cookie.$cookieLine;
    //     // }

    //     if ($raw) {
    //         return $response->getContent();
    //     } elseif (200 == $response->getStatusCode() && 'application/json; charset=utf-8' == $contentType) {
    //         return json_decode($response->getContent());
    //     }

    //     return;
    // }
}

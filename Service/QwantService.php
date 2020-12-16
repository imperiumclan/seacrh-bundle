<?php

namespace ICS\SearchBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpClient\CachingHttpClient;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpKernel\HttpCache\Store;

class QwantService
{
    private $client;

    public function __construct(ContainerInterface $container)
    {
        $store = new Store($container->getParameter('kernel.project_dir').'/var/cache/WebServices/Qwant/');
        $this->client = new CurlHttpClient();

        $this->client = new CachingHttpClient($this->client, $store);
        $this->container = $container;
    }

    public function Search($searchValue, $nbResult = 30, $offset = 0, $type = 'web')
    {
        $requestOptions = [];

        $url = 'https://api.qwant.com/egp/search/'.$type;

        $requestOptions['q'] = $searchValue;
        $requestOptions['offset'] = $offset;

        if ('images' == $type) {
            $options['count'] = $nbResult;
        }

        $options = '';
        if (count($requestOptions) > 0) {
            $options = '?';
            foreach ($requestOptions as $key => $opt) {
                $options .= $key.'='.$opt.'&';
            }
            $options = substr($options, 0, strlen($options) - 1);
        }
        dump($url.$options);
        $response = $this->client->request('GET', $url.$options, [
            'max_redirects' => 5,
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

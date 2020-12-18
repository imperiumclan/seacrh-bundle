<?php

namespace ICS\SearchBundle\Controller;

use ICS\SearchBundle\Service\QwantService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/" , name="ics_search_homepage")
     */
    public function index(Request $request, QwantService $searchService)
    {
        $search = $request->get('search');

        return $this->render('@Search/index.html.twig', [
            'search' => $search,
        ]);
    }

    /**
     * @Route("/search/{type}/{search}/{offset}" , name="ics_search_next_homepage")
     */
    public function search(QwantService $searchService, $search = '', $offset = 0, $type = 'web')
    {
        $response = [];

        if ('' != $search) {
            $res = $searchService->search($search, 30, $offset, $type);
            $response = $res->data->result->items;
        }

        switch ($type) {
            case 'images':
                $result['results'] = $this->renderView('@Search/imageResults.html.twig', [
                    'response' => $response,
                ]);
            break;
            case 'videos':
                $result['results'] = $this->renderView('@Search/videosResults.html.twig', [
                    'response' => $response,
                ]);
            break;
            default:
                $result['results'] = $this->renderView('@Search/webResults.html.twig', [
                    'response' => $response,
                ]);
        }

        $result['next_offset'] = count($response);

        return new JsonResponse($result);
    }

    public function getVideos()
    {
        $videoId = 'ok';
        $url = 'https://www.youtube.com/get_video_info?video_id='.$videoId.'&el=embedded&ps=default&eurl=&gl=US&hl=en';
    }
}

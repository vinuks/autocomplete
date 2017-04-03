<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\AutocompleteBundle\ElasticsearchClient;


class AutocompleteController extends Controller
{
    /**
     * @Route("/autocomplete")
     */
    public function autocompleteAction(Request $request)
    {
        $esClient = $this ->container->get('app.elasticsearch');
        $query = $request->query->get('query');
        $results = $esClient->getDocuments($query);
        $suggestions = [];
        foreach ($results as $result) {

        	$suggestions[] = [ "value" => $result["text"], "data" => "AE" ];
        }

        $response = new Response(json_encode(array('suggestions' => $suggestions)));

        return $response;
    }
}

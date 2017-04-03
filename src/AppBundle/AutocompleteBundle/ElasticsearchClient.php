<?php

namespace AppBundle\AutocompleteBundle;

use Elasticsearch\ClientBuilder;


class ElasticsearchClient {

	
	 public function __construct($indexName)
    {
    	$this->index = $indexName;
    	$this->client = ClientBuilder::create()->build();
    }

	public function createIndex() {
       
        $params = [
    		'index' => $this->index,
		    'body' => [
		        'settings' => [
		            'number_of_shards' => 4,
		            'number_of_replicas' => 1
		        ],
		        'mappings' => [
                    'cities' => [
                        'properties' => [
                            'name' => [
                                'type' => 'string'
                            ],
                            'name_suggest' => [
                                'type' => 'completion',
                                'analyzer' => 'simple',
                                'search_analyzer' => 'simple'
                            ]
                        ],
                    ],
                ]
		    ],
		    	  
		];
		$response = $this->client->indices()->create($params);
    }


    public function addDocument($id, $cityName, $weight) {
    	$params = [
		    'index' => $this->index,
		    'type' => 'cities',
		    'id' => $id,
		    'body' => ['name' => $cityName, 'name_suggest' => ['input' => [$cityName], 'weight' => $weight]]
		];

		$response = $this->client->index($params);
    }



    public function getDocuments($queryWord) {
    	$params = [
		    'index' => $this->index,
		    'type' => 'cities',
		    'body' => [
		        'suggest' => [
		            'city-suggest' => [
		                'prefix' => $queryWord,
		                'completion' => [
		                	'field' => 'name_suggest'
		                ]
		            ]
		        ]
		    ]
		];

		$response = $this->client->search($params);

		return $response['suggest']['city-suggest'][0]['options'];
    }




}
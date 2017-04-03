<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use AppBundle\AutocompleteBundle\ElasticsearchClient;

class IndexingCommand extends ContainerAwareCommand
{
    protected function configure() {
    	$this->setName('app:create-index');  	
        
    }

    protected function loadData() {
        $rows = array_map('str_getcsv', file('../cities.csv'));
        $header = array_shift($rows);
        $csv = array();
        foreach ($rows as $row) {
            $csv[] = array_combine($header, $row);
        }

        return $csv;
    }



    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $elasticsearchClient = $this->getContainer()->get('app.elasticsearch');
        $elasticsearchClient->createIndex();
        $data = $this->loadData();
        foreach ($data as $index => $row) {
            $elasticsearchClient->addDocument($index, $row['city'], $row['count']);
        }
    }
}

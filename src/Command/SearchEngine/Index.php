<?php
namespace ReadShare\Command\SearchEngine;

use ReadShare\Library\SearchEngine\SearchEngine;
use ReadShare\Library\SearchEngine\DocumentModel\DocumentModelType;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Index extends Command {
    /**
     * @var SearchEngine
     */
    private $searchEngine;
    public function __construct(SearchEngine $searchEngine)
    {
        $this->searchEngine = $searchEngine;

        parent::__construct();
    }

    protected function configure() {
        $this->setName("search-engine:index");
        $this->setDescription("Build search engine index");
        $this->addArgument("type", InputArgument::REQUIRED, "Name of model");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $typeName = $input->getArgument("type");
        $type = DocumentModelType::getType($typeName);
        $output->writeln("Start Build index $typeName");
        $this->searchEngine->buildIndex($type);
        $output->writeln("Finish Build index $typeName");
    }
}
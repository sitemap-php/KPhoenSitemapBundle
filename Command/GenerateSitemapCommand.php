<?php

namespace KPhoen\SitemapBundle\Command;

use SitemapGenerator\Sitemap;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateSitemapCommand extends Command
{
    protected static $defaultName = 'sitemap:generate';

    private $sitemap;

    public function __construct(Sitemap $sitemap)
    {
        parent::__construct();
        $this->sitemap = $sitemap;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Generate the sitemap')
            ->setName('sitemap:generate');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Generating the sitemap... ');
        $output->writeln($this->sitemap->build());
        $output->writeln(PHP_EOL . '<info>Done!</info>');

        return 0;
    }
}

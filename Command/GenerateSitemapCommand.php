<?php

namespace KPhoen\SitemapBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class GenerateSitemapCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDescription('Generate the sitemap')
            ->setName('sitemap:generate');
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sitemap = $this->getContainer()->get('sitemap');

        $output->writeln('Generating the sitemap... ');
        $output->writeln($sitemap->build());
        $output->writeln(PHP_EOL . '<info>Done!</info>');
    }
}

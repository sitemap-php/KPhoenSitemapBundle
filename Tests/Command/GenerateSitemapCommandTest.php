<?php

namespace KPhoen\SitemapBundle\Tests\Controller;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use KPhoen\SitemapBundle\Command\GenerateSitemapCommand;

class GenerateSitemapCommandTest extends WebTestCase
{
    public function testSitemapNbUrls()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new GenerateSitemapCommand());

        $command = $application->find('sitemap:generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertRegExp('`http://www.google.fr`', $commandTester->getDisplay());
        $this->assertRegExp('`http://github.com`', $commandTester->getDisplay());
    }
}

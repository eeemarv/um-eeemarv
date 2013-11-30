<?php

namespace Eeemarv\EeemarvBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class CodeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('eeemarv:code')
            ->setDescription('Get the Code of this Lets Group.')             
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$code = $this->getContainer()->getParameter('code');
        $output->writeln($code);          
    }    
}

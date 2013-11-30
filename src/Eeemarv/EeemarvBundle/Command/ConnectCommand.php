<?php

namespace Eeemarv\EeemarvBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class ConnectCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('eeemarv:connect')
            ->setDescription('Direct Api for Lets-group connection. // not implemented yet.')
            ->addArgument('code', InputArgument::REQUIRED)
            ->addArgument('url', InputArgument::REQUIRED)
            ->addArgument('unique-id', InputArgument::REQUIRED)
            ->addArgument('key', InputArgument::REQUIRED)             
                        
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $code = $input->getArgument('code');
        $url = $input->getArgument('url');
        $uniqueId = $input->getArgument('unique-id');		
		$key = $input->getArgument('key');

		
        $output->writeln(sprintf('Request submitted'));          
    }
    


    
}

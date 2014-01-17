<?php

namespace Eeemarv\EeemarvBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class CronCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('eeemarv:cron')
            ->setDescription('Run the cron task. Recommanded frequency: every 15 minutes.')             
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

		
		
		
		
		
        $output->writeln('done.');          
    }    
}

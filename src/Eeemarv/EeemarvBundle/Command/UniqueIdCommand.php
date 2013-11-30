<?php

namespace Eeemarv\EeemarvBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class UniqueIdCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('eeemarv:unique-id')
            ->setDescription('Get the Unique Id of this Lets Group.')             
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$uniqueId = $this->getContainer()->getParameter('unique_id');
        $output->writeln($uniqueId);          
    }    
}

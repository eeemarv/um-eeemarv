<?php

namespace Eeemarv\EeemarvBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Eeemarv\EeemarvBundle\Entity\Place;

class PlacesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('eeemarv:places')
            ->setDescription('List places.')          
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {	
		$em = $this->getContainer()->get('doctrine')->getEntityManager();

		$q = $em->createQueryBuilder()			// to be moved to repository
			->select('p.id, p.postalCode, t.name, p.country') 
			->from('EeemarvBundle:Place', 'p')
			->leftJoin('p.defaultTranslation', 't')
			->getQuery();	
		$placeArray =  $q->getArrayResult();		
		
		$table = $this->getHelperSet()->get('table');
		$table->setHeaders(array('id', 'postal code', 'name', 'country'))
			->setRows($placeArray);
		$table->render($output);

    }
    
    protected function interact(InputInterface $input, OutputInterface $output)
    {

    }    
    
    
    
}

<?php

namespace Eeemarv\EeemarvBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Eeemarv\EeemarvBundle\Entity\Place;

class UsersNewCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('eeemarv:system-users:create')
            ->setDescription('Create the active and inactive system-users.')
            ->addArgument('place-id', InputArgument::REQUIRED, 'Place')
            ->addArgument('street', InputArgument::REQUIRED, 'Street ')
            ->addArgument('house-number', InputArgument::REQUIRED, 'House number')
            ->addArgument('box', InputArgument::OPTIONAL, 'Box')
            ->addArgument('name-active', InputArgument::OPTIONAL, 'Name of the active administration account.')
            ->addArgument('name-inactive', InputArgument::OPTIONAL, 'Name of the inactive administration account.')            
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {		
		$em = $this->getContainer()->get('doctrine')->getEntityManager();
		$query = $em->createQuery('select p.id, p.postalCode, p.name, p.country from Eeemarv\EeemarvBundle\Entity\Place p');
		$places = $query->getArrayResult();
		if (sizeof($places)){
			$table = $this->getHelperSet()->get('table');
			$table->setHeaders(array('id', 'postal code', 'name', 'country'))
				->setRows($places);
			$table->render($output);
		} else {
			throw new \Exception('Please create a place first.');
		}			
        $placeId = $input->getArgument('place-id');
        
        
        $name = $input->getArgument('place');
        $countryCode = $input->getArgument('country-code');		

        $place  = new Place();

        $place->setPostalCode($postalCode);
        $place->setName($name);        
        $place->setCountry($countryCode);
             

		$em->persist($place);		
		$em->flush();
		
        $output->writeln(sprintf('Created place <comment>%s</comment>', $place));          
    }
    
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('postal-code')) {
            $postalCode = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a postal code:',
                function($postalCode) {
                    if (empty($postalCode)) {
                        throw new \Exception('Postal code can not be empty');
                    }

                    return $postalCode;
                },
                2
            );
            $input->setArgument('postal-code', $postalCode);
        }

        if (!$input->getArgument('place-name')) {
            $placeName = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a place name (in the default language):',
                function($placeName) {
                    if (empty($placeName)) {
                        throw new \Exception('Place name can not be empty');
                    }

                    return $placeName;
                },
                2
            );
            $input->setArgument('place-name', $placeName);
        }

        if (!$input->getArgument('country-code')) {
            $countryCode = $this->getHelper('dialog')->askAndValidate(
                $output,
                'Please choose a two character iso country code:',
                function($countryCode) {
                    if (empty($countryCode)) {
                        throw new \Exception('Country code can not be empty');
                    }
					if (strlen($countryCode) > 2) {
                        throw new \Exception('Country code can only be two characters');
                    }
                    return strtoupper($countryCode);
                },
                2
            );
            $input->setArgument('country-code', $countryCode);
        }
    }      
}

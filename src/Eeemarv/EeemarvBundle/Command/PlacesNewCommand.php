<?php

namespace Eeemarv\EeemarvBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Eeemarv\EeemarvBundle\Entity\Place;

class PlacesNewCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('eeemarv:places:new')
            ->setDescription('Add a place with postal code, name and country code where your Lets-group operates.')
            ->addArgument('postal-code', InputArgument::REQUIRED, 'The postal code')
            ->addArgument('place-name', InputArgument::REQUIRED, 'The placename (in the default language)')
            ->addArgument('country-code', InputArgument::REQUIRED, 'The two character country code (iso)')            
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $postalCode = $input->getArgument('postal-code');
        $name = $input->getArgument('place-name');
        $countryCode = $input->getArgument('country-code');		

        $place  = new Place();

        $place->setPostalCode($postalCode);
        $place->setName($name);        
        $place->setCountry($countryCode);
             
		$em = $this->getContainer()->get('doctrine')->getEntityManager();
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
                    return strtolower($countryCode);
                },
                2
            );
            $input->setArgument('country-code', $countryCode);
        }
    }      
}

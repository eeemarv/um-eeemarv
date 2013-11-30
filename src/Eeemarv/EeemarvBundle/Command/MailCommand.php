<?php

namespace Eeemarv\EeemarvBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use MimeMailParser\Parser;
use MimeMailParser\Attachment;



class MailCommand extends ContainerAwareCommand
{
	private $returnErrors = array();
	private $domain = 'localhost';
	
	
    protected function configure()
    {
        $this
            ->setName('eeemarv:mail')
            ->setDescription('pipes mails. use -q option. mailParse php extension has to be enabled.')
            ->addArgument('path', InputArgument::OPTIONAL, 'The path to the email-file.')            
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		$parser = new Parser();
				
		$path = $input->getArgument('path');
		if ($path){
			$parser->setPath($path);
		} else {
			$parser->setStream(fopen('php://stdin', 'r'));
		}
		
		if ($parser->getHeader('cc')){
			exit;    // feedback error (todo)
		}

		$subject = $parser->getHeader('subject');

		if (!$subject){
			exit;	// feedback error (todo)
		}	

		list($toId, $toDomain) = $this->decompose($parser->getHeader('to'));
		list($fromId, $fromDomain) = $this->decompose($parser->getHeader('from'), $toDomain);
		list($uniqueId, $domain) = $this->decompose($parser->getHeader('message-id'), $toDomain);
		
		$returnPath = $parser->getHeader('return-path');
		
		$body = $parser->getMessageBody('html');
		
		if (!$body){		
			$body = $parser->getMessageBody('text');
			if (!$body){
				exit;
			}
		}
		
		
		
	/*			
		
			
		$attachments = $parser->getAttachments();
		foreach ($attachments as $attachment)
		{
			$filename = $attachment->filename;
			if ($f = fopen($save_dir.$filename, 'w')) 
			{
				while($bytes = $attachment->read()) 
				{
				  fwrite($f, $bytes);
				}
				fclose($f);
			}
		}
		*/
		
		$output->writeln('from id:'.$fromId);
      $output->writeln('to id:'.$toId);
      $output->writeln('subject:'.$subject);
      

      $output->writeln('return-path:'.$returnPath);

      $output->writeln('message-id:'.$uniqueId.'@'.$domain);
            $output->writeln('unique-id:'.$uniqueId);
        $output->writeln('domain:'.$domain); 
        
      $output->writeln('body:'.$body); 
       $output->writeln('html:'.$html);                    
                
    } 

	
	private function decompose($address, $compareDomain = null)
	{
		$addressAry = mailparse_rfc822_parse_addresses($address);
		
		if (!sizeOf($addressAry)){			// missing address
			exit;
		}	
		
		if (sizeOf($addressAry) > 1){			// more than one address  (feedback error - todo )
			exit;							
		}
			
		$address = $addressAry[0]['address'];		

		list($id, $domain) = explode('@', $address);
		
		if (!$id || !$domain || $domain == $compareDomain){
			exit;
		}		
	
		return array($id, $domain);
	}	
	
	   
}

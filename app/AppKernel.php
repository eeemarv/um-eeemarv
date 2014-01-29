<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
        
			// Symfony Standard Edition
			
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            
            // 
 
			new JMS\AopBundle\JMSAopBundle(),           
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
			new JMS\DiExtraBundle\JMSDiExtraBundle($this),                     
			new JMS\SerializerBundle\JMSSerializerBundle(),
			new JMS\I18nRoutingBundle\JMSI18nRoutingBundle(),
			new JMS\TranslationBundle\JMSTranslationBundle(),		
			
						          
			new Ivory\CKEditorBundle\IvoryCKEditorBundle(),           
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
			new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
			new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),            
            new EWZ\Bundle\RecaptchaBundle\EWZRecaptchaBundle(),
            
            new FOS\UserBundle\FOSUserBundle(), 
 			new FOS\RestBundle\FOSRestBundle(),                                 
			new Vich\GeographicalBundle\VichGeographicalBundle(),  
			new Liip\ImagineBundle\LiipImagineBundle(),
			
			new Bc\Bundle\BootstrapBundle\BcBootstrapBundle(),
//            new Braincrafted\Bundle\BootstrapBundle\BraincraftedBootstrapBundle(),
			new Bmatzner\FontAwesomeBundle\BmatznerFontAwesomeBundle(),
			new Bmatzner\JQueryBundle\BmatznerJQueryBundle(),
			new Bmatzner\JQueryUIBundle\BmatznerJQueryUIBundle(),						
			
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),			 
 			new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
			new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(), 
 
 			new Lexik\Bundle\MaintenanceBundle\LexikMaintenanceBundle(),
 			new EightPoints\Bundle\GuzzleBundle\GuzzleBundle(),
            new Escape\WSSEAuthenticationBundle\EscapeWSSEAuthenticationBundle(),
            
			new Oneup\UploaderBundle\OneupUploaderBundle(),    

            new Lsw\MemcacheBundle\LswMemcacheBundle(),
            
     
			// 
  
            new Eeemarv\EeemarvBundle\EeemarvBundle(),           
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}

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
            
            // JMS
 
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

            new Braincrafted\Bundle\BootstrapBundle\BraincraftedBootstrapBundle(),
			new Bmatzner\JQueryBundle\BmatznerJQueryBundle(),
								
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),			 
 			new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
			new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(), 
 
 			new Lexik\Bundle\MaintenanceBundle\LexikMaintenanceBundle(),
 			new EightPoints\Bundle\GuzzleBundle\GuzzleBundle(),
            new Escape\WSSEAuthenticationBundle\EscapeWSSEAuthenticationBundle(),
            
			new Oneup\UploaderBundle\OneupUploaderBundle(),    

            new Lsw\MemcacheBundle\LswMemcacheBundle(),
            
     
			// 


			new Sonata\CacheBundle\SonataCacheBundle(),
			new Sonata\CoreBundle\SonataCoreBundle(),
			new Sonata\BlockBundle\SonataBlockBundle(),			
			new Sonata\SeoBundle\SonataSeoBundle(),
			new Sonata\NotificationBundle\SonataNotificationBundle(),
			new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
			new Sonata\PageBundle\SonataPageBundle(),
			new Sonata\jQueryBundle\SonatajQueryBundle(),
			new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
			new Sonata\AdminBundle\SonataAdminBundle(),
			new Sonata\MediaBundle\SonataMediaBundle(),
			new Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),
			new Application\Sonata\PageBundle\ApplicationSonataPageBundle(),
			new Application\Sonata\NotificationBundle\ApplicationSonataNotificationBundle(),
			new Application\Sonata\MediaBundle\ApplicationSonataMediaBundle(),
			
			
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

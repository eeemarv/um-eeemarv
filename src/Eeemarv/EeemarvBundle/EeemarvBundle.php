<?php

namespace Eeemarv\EeemarvBundle;

use Eeemarv\EeemarvBundle\DependencyInjection\Security\Factory\WsseFactory;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EeemarvBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
    
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new WsseFactory());
    }      		
}

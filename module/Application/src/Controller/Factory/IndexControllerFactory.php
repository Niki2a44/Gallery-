<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Model\ImageModel;
use Application\Controller\IndexController;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, 
                        $requestedName, array $options = null)
    {
        $imageManager = $container->get(ImageModel::class);
        
        
        return new IndexController($imageManager);
    }
}
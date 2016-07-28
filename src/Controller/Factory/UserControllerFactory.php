<?php
/**
 * Supplier Controller Factory
 *
 * @category Agere
 * @package Agere_Supplier
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 04.04.2016 0:19
 */
namespace Agere\User\Controller\Factory;

use Agere\User\Controller\UserController;

class UserControllerFactory
{
    public function __invoke($cm)
    {
        $sm = $cm->getServiceLocator();
        $controller = new UserController();
        $controller->setServiceManager($sm);

        return $controller;
    }
}

<?php
namespace Agere\User;

return array(
    'controllers' => array(
        'aliases' => [
            'user' => Controller\UserController::class,

        ],
        'factories' => [
            Controller\UserController::class => Controller\Factory\UserControllerFactory::class,
        ],
        
        
    ),
    'view_manager' => array(
        'template_map' => array(
            'users/children-index' => __DIR__ . '/../view/magere/users/children/index/index.phtml',
            'users/children-monitoring' => __DIR__ . '/../view/magere/users/children/monitoring/index.phtml',
            'users/edit/basic-data' => __DIR__ . '/../view/magere/users/tabs/edit/basic-data.phtml',
            'user-info' => __DIR__ . '/../view/agere/user/user-info.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'controller_plugins' => [
        'factories' => [
            'user' => Controller\Plugin\Factory\UserFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'user' => View\Helper\UserHelper::class,
        ],
        /*'factories' => [
            'user' => 'Magere\Users\View\Helper\Factory\UserFactory',
        ],*/
    ],
    'service_manager' => array(
        'aliases' => array(
            'UserService' => Service\UserService::class,
            'UserAuthentication' => Controller\Plugin\UserAuthentication::class,
            'UsersRolesService' => Service\UsersRolesService::class,
            'UserGrid' => Block\Grid\UserGrid::class, // only for GridFactory
        ),
        'invokables' => [
            Service\UserService::class => Service\UserService::class,
            Service\UsersRolesService::class => Service\UsersRolesService::class,
            Model\User::class => Model\User::class,
            Model\UsersRoles::class => Model\UsersRoles::class,
        ],
        'factories' => array(
            Event\Authentication::class => Event\Factory\AuthenticationFactory::class,

            Controller\Plugin\UserAuthentication::class => function ($sm) {
                    $authAdapter = $sm->get(Authentication\Adapter\DbTable\CredentialTreatmentAdapter::class);
                    $userAuthentication = new \Agere\User\Controller\Plugin\UserAuthentication();
                    //$userAuthentication->setController();
                    $userAuthentication->setAuthAdapter($authAdapter);

                    return $userAuthentication;
                },
                Authentication\Adapter\DbTable\CredentialTreatmentAdapter::class => function ($sm) {
                    $zendDb = $sm->get('Zend\Db\Adapter\Adapter');
                    $tableName = 'user';
                    $identityColumn = 'email';
                    $credentialColumn = 'password';
                    $credentialTreatment = '?';
                    $adapter = new \Agere\User\Authentication\Adapter\DbTable\CredentialTreatmentAdapter(
                        $zendDb,
                        $tableName,
                        $identityColumn,
                        $credentialColumn,
                        $credentialTreatment
                    );

                    return $adapter;
                },
        ),
    ),

    // Doctrine config
    'doctrine' => array(
        'driver' => array(
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Model' => __NAMESPACE__ . '_driver',
                )
            ),
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\YamlDriver',
                'cache' => 'array',
                'extension' => '.dcm.yml',
                'paths' => array(__DIR__ . '/yaml')
            ),
        ),
    ),
    // @link http://adam.lundrigan.ca/2012/07/quick-and-dirty-zf2-zend-navigation/
    // All navigation-related configuration is collected in the 'navigation' key
    /*'navigation' => array(
        // The DefaultNavigationFactory we configured in (1) uses 'default' as the sitemap key
        'default' => array(
            // And finally, here is where we define our page hierarchy
            'users' => array(
                'module' => 'users',
                'label' => 'Главная',
                'route' => 'default',
                'controller' => 'index',
                'action' => 'index',
                'pages' => array(
                    'settings-index' => array(
                        'label' => 'Настройки',
                        'route' => 'default',
                        'controller' => 'settings',
                        'action' => 'index',
                        'pages' => array(
                            'users-index' => array(
                                'label' => 'Пользователи',
                                'route' => 'default',
                                'controller' => 'users',
                                'action' => 'index',
                                'pages' => array(
                                    'users-add' => array(
                                        'label' => 'Добавить',
                                        'route' => 'default',
                                        'controller' => 'users',
                                        'action' => 'add',
                                    ),
                                    'users-edit' => array(
                                        'label' => 'Редактировать',
                                        'route' => 'default/id',
                                        'controller' => 'users',
                                        'action' => 'edit',
                                    ),
                                    'users-history' => array(
                                        'label' => 'Редактировать',
                                        'route' => 'default/id',
                                        'controller' => 'users',
                                        'action' => 'history',
                                    ),
                                ),
                            ),
                            'users-index-wildcard' => array(
                                'label' => 'Пользователи',
                                'route' => 'default/id/wildcard',
                                'controller' => 'users',
                                'action' => 'index',
                            ),
                            'users-index-id' => array(
                                'label' => 'Пользователи',
                                'route' => 'default/id',
                                'controller' => 'users',
                                'action' => 'index',
                            ),
                            'users-monitoring' => array(
                                'label' => 'Мониторинг пользователей',
                                'route' => 'default',
                                'controller' => 'users',
                                'action' => 'monitoring',
                            ),
                            'users-monitoring-wildcard' => array(
                                'label' => 'Мониторинг пользователей',
                                'route' => 'default/id/wildcard',
                                'controller' => 'users',
                                'action' => 'monitoring',
                            ),
                            'users-change-password' => array(
                                'label' => 'Личные данные',
                                'route' => 'default',
                                'controller' => 'users',
                                'action' => 'change-password',
                            ),
                        ),
                    ),
                    'staff-index-id' => array(
                        'label' => 'Наши сотрудники',
                        'route' => 'default/id',
                        'controller' => 'staff',
                        'action' => 'index',
                        'pages' => array(
                            'staff-edit' => array(
                                'label' => 'Редактировать',
                                'route' => 'default/id',
                                'controller' => 'staff',
                                'action' => 'edit',
                            ),
                        ),
                    ),
                    'staff-index' => array(
                        'label' => 'Наши сотрудники',
                        'route' => 'default',
                        'controller' => 'staff',
                        'action' => 'index',
                    ),
                    'staff-index-wildcard' => array(
                        'label' => 'Наши сотрудники',
                        'route' => 'default/id/wildcard',
                        'controller' => 'staff',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),*/
);
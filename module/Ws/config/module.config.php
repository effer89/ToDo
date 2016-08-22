<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Ws;

return array(
    'router' => array(
        'routes' => array(
            'ws' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/ws',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Ws\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'to-do' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/ws/to-do[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Ws\Controller\ToDo',
                    ),
                ),
            ),
        ),
    ),
    // Placeholder for console routes
    'console' => array(
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Ws\Controller\ToDo' => Controller\ToDoController::class,
            'Ws\Controller\Index' => Controller\IndexController::class,
        ),
    ),
    'view_manager' => array( //Add this config
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    // Doctrine config
    'doctrine' => array(
        'configuration' => array(
            'orm_default' => array(
                'numeric_functions' => array(
                    'Replace' => 'DoctrineExtensions\Query\Mysql\Replace'
                ),
                'datetime_functions' => array(),
                'string_functions'   => array(),
                'metadata_cache'     => 'filesystem',
                'query_cache'        => 'filesystem',
                'result_cache'       => 'filesystem',
            )
        ),
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    )
);

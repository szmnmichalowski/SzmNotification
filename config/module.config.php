<?php

namespace SzmNotification;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controller_plugins' => [
        'aliases' => [
            'notification' => Controller\Plugin\Notification::class
        ],
        'factories' => [
            Controller\Plugin\Notification::class => InvokableFactory::class
        ]
    ],
];
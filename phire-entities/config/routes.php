<?php

return [
    APP_URI => [
        '/entities[/]' => [
            'controller' => 'Phire\Entities\Controller\IndexController',
            'action'     => 'index',
            'acl'        => [
                'resource'   => 'entities',
                'permission' => 'index'
            ]
        ],
        '/entities/add[/]' => [
            'controller' => 'Phire\Entities\Controller\IndexController',
            'action'     => 'add',
            'acl'        => [
                'resource'   => 'entities',
                'permission' => 'add'
            ]
        ],
        '/entities/edit/:id' => [
            'controller' => 'Phire\Entities\Controller\IndexController',
            'action'     => 'edit',
            'acl'        => [
                'resource'   => 'entities',
                'permission' => 'edit'
            ]
        ],
        '/entities/remove[/]' => [
            'controller' => 'Phire\Entities\Controller\IndexController',
            'action'     => 'remove',
            'acl'        => [
                'resource'   => 'entities',
                'permission' => 'remove'
            ]
        ]
    ]
];

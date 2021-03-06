<?php
/**
 * phire-entities routes
 */
return [
    APP_URI => [
        '/entities[/:tid]' => [
            'controller' => 'Phire\Entities\Controller\IndexController',
            'action'     => 'index',
            'acl'        => [
                'resource'   => 'entities',
                'permission' => 'index'
            ]
        ],
        '/entities/add/:tid' => [
            'controller' => 'Phire\Entities\Controller\IndexController',
            'action'     => 'add',
            'acl'        => [
                'resource'   => 'entities',
                'permission' => 'add'
            ]
        ],
        '/entities/edit/:tid/:id' => [
            'controller' => 'Phire\Entities\Controller\IndexController',
            'action'     => 'edit',
            'acl'        => [
                'resource'   => 'entities',
                'permission' => 'edit'
            ]
        ],
        '/entities/remove/:tid' => [
            'controller' => 'Phire\Entities\Controller\IndexController',
            'action'     => 'remove',
            'acl'        => [
                'resource'   => 'entities',
                'permission' => 'remove'
            ]
        ],
        '/entities/export/:tid' => [
            'controller' => 'Phire\Entities\Controller\IndexController',
            'action'     => 'export',
            'acl'        => [
                'resource'   => 'entities',
                'permission' => 'export'
            ]
        ],
        '/entities/types[/]' => [
            'controller' => 'Phire\Entities\Controller\TypeController',
            'action'     => 'index',
            'acl'        => [
                'resource'   => 'entity-types',
                'permission' => 'index'
            ]
        ],
        '/entities/types/add[/]' => [
            'controller' => 'Phire\Entities\Controller\TypeController',
            'action'     => 'add',
            'acl'        => [
                'resource'   => 'entity-types',
                'permission' => 'add'
            ]
        ],
        '/entities/types/edit/:id' => [
            'controller' => 'Phire\Entities\Controller\TypeController',
            'action'     => 'edit',
            'acl'        => [
                'resource'   => 'entity-types',
                'permission' => 'edit'
            ]
        ],
        '/entities/types/remove[/]' => [
            'controller' => 'Phire\Entities\Controller\TypeController',
            'action'     => 'remove',
            'acl'        => [
                'resource'   => 'entity-types',
                'permission' => 'remove'
            ]
        ]
    ]
];
